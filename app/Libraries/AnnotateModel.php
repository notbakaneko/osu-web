<?php

/**
 *    Copyright 2015-2018 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Libraries;

use DB;

use ReflectionClass;
use ReflectionMethod;
use App\Libraries\HasDynamicTable;
use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Microsoft\PhpParser\Node\DelimitedList\ArgumentExpressionList;
use Microsoft\PhpParser\Node\Expression\ArgumentExpression;
use Microsoft\PhpParser\Node\StringLiteral;
use Microsoft\PhpParser\Node\QualifiedName;
use Microsoft\PhpParser\Node\Expression\Variable;
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;
use Microsoft\PhpParser\Node\Statement\ReturnStatement;
use Microsoft\PhpParser\Node;
use Microsoft\PhpParser\Parser;
use Symfony\Component\Finder\SplFileInfo;
use File;
use Microsoft\PhpParser\Token;
use Microsoft\PhpParser\TokenKind;

class AnnotateModel
{
    const STRING_TYPES = ['char', 'varchar', 'text'];
    const INT_TYPES = ['int', 'smallint', 'mediumint', 'bigint', 'tinyint'];
    const FLOAT_TYPES = ['decimal', 'float', 'double'];
    const DATE_TYPES = ['date', 'timestamp'];

    /** @var SplFileInfo */
    private $file;

    /** @var array */
    private $properties;

    /** @var Model */
    private $instance;

    /** @var Parser */
    private $parser;

    /** @var Node */
    private $astNode;

    /** @var string */
    private $content;

    /** @var array */
    private $methodDeclarations;

    public static function fromClass($class)
    {
        $reflectionClass = new ReflectionClass($class);
        $file = new SplFileInfo($reflectionClass->getFilename(), '', '');

        return new static($file);
    }

    public static function classFromFileInfo(SplFileInfo $fileInfo)
    {
        $baseName = $fileInfo->getBasename(".{$fileInfo->getExtension()}");
        $namespace = str_replace('/', '\\', $fileInfo->getRelativePath());
        if (mb_strlen($fileInfo->getRelativePath()) !== 0) {
            $namespace .= '\\';
        }

        return "\\App\\Models\\{$namespace}{$baseName}";
    }

    public static function describeTable(Model $instance)
    {
        $table = $instance->getTable();
        return $instance->getConnection()->select("DESCRIBE `{$table}`");
    }

    public static function getTables(string $connectionName = null)
    {
        return DB::connection($connectionName)->select('SHOW TABLES');
    }

    public static function relationshipMethods()
    {
        static $methods = null;

        if ($methods === null) {
            // extract names of eloquent relationship methods
            $reflectionClass = new ReflectionClass('Illuminate\Database\Eloquent\Concerns\HasRelationships');
            $methods = collect($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))
                ->map(function ($method) {
                    return $method->name;
                })->all();
        }

        return $methods;
    }

    public static function collectionRelationshipMethods()
    {
        static $methods = null;

        if ($methods === null) {
            $methods = array_values(array_intersect(
                static::relationshipMethods(),
                ['belongsToMany', 'hasMany', 'hasManyThrough', 'morphToMany', 'morphedByMany']
            ));
        }

        return $methods;
    }

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;

        $class = static::classFromFileInfo($file);
        $reflectionClass = new ReflectionClass($class);
        if (
            $reflectionClass->isAbstract() ||
            !$reflectionClass->isSubclassOf(Model::class) ||
            $reflectionClass->implementsInterface(HasDynamicTable::class)
        ) {
            return;
        }

        $this->instance = $class::first() ?? new $class;
        $this->parser = new Parser();
    }

    public function addProperty(string $name, string $type, ?string $text = null)
    {
        $existingType = $this->properties[$name]['type'] ?? null;

        // override existing property if doesn't exist or mixed
        if ($existingType === null || $existingType === 'mixed') {
            $this->properties[$name] = ['type' => $type, 'text' => $text];
        }
    }

    public function getClassDeclaration()
    {
        return $this->classDeclaration;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getMethodDeclarations()
    {
        return $this->methodDeclarations;
    }

    public function getMethodNames()
    {
        return $this->methodNames;
    }

    public function annotate()
    {
        if ($this->instance === null) {
            return;
        }

        $this->astNode = $this->parse();

        $this->findDirectProperties();
        $this->findPropertiesFromMethods();
        $this->findPropertiesFromAttributes();

        $this->addAnnotationsToFile();
    }

    public function parse()
    {
        $this->content = $this->file->getContents();
        $astNode = $this->parser->parseSourceFile($this->content);

        $this->classDeclaration = null;
        foreach ($astNode->statementList as $statement) {
            if ($statement instanceof ClassDeclaration) {
                $this->className = $statement->getNameParts()[0]->getText($this->content);

                $this->classDeclaration = $statement;
                break;
            }
        }

        $this->methodDeclarations = $this->parseMethodDeclarations();

        $this->attributeNames = collect($this->methodDeclarations)->filter(function ($item) {
            return ends_with($item->getName(), 'Attribute');
        })->map(function ($item) {
            $name = $item->getName();
            return snake_case(substr(substr($name, 3), 0, -9));
        })->all();

        return $astNode;
    }

    public function parseMethodDeclarations()
    {
        $methods = [];
        $classMemberDeclarations = $this->classDeclaration->classMembers->classMemberDeclarations;
        foreach ($classMemberDeclarations as $declaration) {
            if ($declaration instanceof MethodDeclaration) {
                $methods[] = $declaration;
            }
        }

        return $methods;
    }

    public function findDirectProperties()
    {
        $columns = static::describeTable($this->instance);

        foreach ($columns as $column) {
            [$name, $type] = $this->parseColumn($column);
            $this->addProperty($name, $type);
        }
    }

    public function findPropertiesFromAttributes()
    {
        foreach ($this->attributeNames as $name) {
            // TODO: should check the types and see if it needs to be overridden or not.
            $this->addProperty($name, 'mixed');
        }
    }

    public function findPropertiesFromMethods()
    {
        $properties = [];

        foreach ($this->methodDeclarations as $declaration) {
            if ($this->tryExtractRelationship($declaration) !== null) {
                /** @var Node[] $statements */
                $statements = $declaration->compoundStatementOrSemicolon->statements;

                // only consider methods with only single return statement for now.
                if (count($statements) !== 1) {
                    continue;
                }

                /** @var Node $statement */
                $statement = $statements[0];
                if ($statement->getNodeKindName() !== 'ReturnStatement') {
                    continue;
                }

                $nodes = $statement->getChildNodes();
                foreach ($nodes as $node) {
                    if ($node->getNodeKindName() !== 'CallExpression') {
                        continue;
                    }

                    // print_r($node->getText());
                    // print_r("\n");
                    [$function, $className] = $this->walkCallExpression($node);

                    if ($className !== null) {
                        $isCollection = in_array($function, static::collectionRelationshipMethods(), true);
                        $this->addProperty(
                            $declaration->getName(),
                            // PSR-5 removed generics recently, so no standard typehint format for typed collections
                            $isCollection ? '\Illuminate\Database\Eloquent\Collection' : $className,
                            $isCollection ? $className : null
                        );
                    }
                }

                // echo "-----\n";
            }
        }

        return $properties;
    }

    private function tryExtractRelationship(MethodDeclaration $declaration)
    {
        if (ends_with($declaration->getName(), 'Attribute') || $declaration->parameters !== null) {
            return null;
        }

        return $declaration;
    }


    public function addAnnotationsToFile()
    {
        $text = '';
        foreach ($this->properties as $name => $property) {
            $text .= " * @property {$property['type']} \${$name}";
            if (!empty($property['text'])) {
                $text .= " {$property['text']}";
            }

            $text .= "\n";
        }

        $node = $this->classDeclaration;

        if ($node === null) {
            echo("Could not find class declaration in {$this->file->getFilename()}!");

            return;
        }

        $existingComment = $node->getLeadingCommentAndWhitespaceText();
        $existingCommentLength = strlen($existingComment);

        $blockExists = starts_with(trim($existingComment), '/**') && ends_with(trim($existingComment), '*/');
        if ($blockExists) {
            $lines = explode("\n", $existingComment);
            $lines = array_values(array_filter($lines, function ($line) {
                return !(starts_with($line, ' * @property') || starts_with($line, ' */'));
            }));

            // hack to prevent more empty lines from being added each run.
            $numLines = count($lines);
            if ($numLines >= 2 && trim($lines[$numLines - 2]) === '*') {
                array_pop($lines);
                array_pop($lines);
            }

            $text = implode("\n", $lines)."\n *\n".$text." */\n";
        } else {
            $text = "\n\n/**\n *\n".$text." */\n";
        }

        $newContent = substr_replace($this->content, $text, $node->getStart() - $existingCommentLength, $existingCommentLength);
        File::put($this->file->getRealPath(), $newContent);
    }

    public function texify($node) {
        if ($node instanceof Node) {
            print_r($node->getText());
            print_r(' ;Node;  '.get_class($node));//->getNodeKindName());
            // print_r($node->getChildNames());
            print_r("\n");
        } elseif ($node instanceof Token) {
            print_r($node->getText());
            print_r(' ;Token;  '.print_r($node->jsonSerialize(), true));
            print_r("\n");
        }
    }

    private function castType(string $field, string $type) : string
    {
        $cast = $this->instance->getCasts()[$field] ?? null;
        if ($cast !== null) {
            return $cast;
        }

        if (starts_with($type, static::STRING_TYPES)) {
            return 'string';
        }

        if (starts_with($type, static::INT_TYPES)) {
            return 'int';
        }

        if (starts_with($type, static::FLOAT_TYPES)) {
            return 'float';
        }

        if (starts_with($type, static::DATE_TYPES)) {
            return '\Carbon\Carbon';
        }

        return 'mixed';
    }

    // Field, Type, Null, Key, Default, Extra
    private function parseColumn($column)
    {
        $type = $this->parseType($column);

        return [$column->Field, $type];
    }

    /**
     * Budget hacky type parser
     *
     * @param string $type
     * @return string
     */
    private function parseType($column) : string
    {
        $type = $this->castType($column->Field, $column->Type);

        if ($column->Null !== "NO") {
            $type = $type.'|null';
        }

        return $type;
    }

    private function tryFindRelationship(Node $node) : ?array {
        // $this->texify($node);
        // keep going until probable relationship method.
        if ($node->getNodeKindName() !== 'MemberAccessExpression') {
            return null;
        }
        // $this->texify($node);

        $children = iterator_to_array($node->getChildNodesAndTokens());
        $maybeThis = array_first($children);
        $maybeFunctionName = array_last($children);

        if ($maybeThis === null
            || $maybeFunctionName === null
            || !($maybeThis instanceof Variable)
            || !($maybeFunctionName instanceof Token)
            || $maybeFunctionName->kind !== TokenKind::Name
            || $maybeThis->getName() !== 'this'
        ) {
            return null;
        }

        $functionName = $maybeFunctionName->getText($node->getFileContents());
        if (!in_array($functionName, static::relationshipMethods(), true)) {
            return null;
        }

        // morphTo can be anything :v
        if ($functionName === 'morphTo') {
            return [$functionName, 'mixed'];
        }

        $parent = $node->getParent();
        if ($parent->getNodeKindName() !== 'CallExpression') {
            // TODO: should probably log message
            return null;
        }

        $expressionList = $parent->getFirstChildNode(ArgumentExpressionList::class);
        if ($expressionList === null) {
            return null;
        }

        $argumentExpression = $expressionList->getFirstChildNode(ArgumentExpression::class);
        if ($argumentExpression === null) {
            return null;
        }

        $className = $argumentExpression->getFirstDescendantNode(QualifiedName::class, StringLiteral::class);
        if ($className === null) {
            return null;
        }

        return [$functionName, $className->getText()];
    }

    private function walkCallExpression(Node $expression) {
        $nodes = $expression->getDescendantNodes();
        $this->texify($expression);
        foreach ($nodes as $node) {
            // $this->texify($node);
            $relationship = $this->tryFindRelationship($node);
            if ($relationship !== null) {
                return $relationship;
            }
        }
    }
}
