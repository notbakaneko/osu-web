<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Libraries;

use File;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Microsoft\PhpParser\Node;
use Microsoft\PhpParser\Node\DelimitedList\ArgumentExpressionList;
use Microsoft\PhpParser\Node\Expression\ArgumentExpression;
use Microsoft\PhpParser\Node\Expression\CallExpression;
use Microsoft\PhpParser\Node\Expression\MemberAccessExpression;
use Microsoft\PhpParser\Node\Expression\Variable;
use Microsoft\PhpParser\Node\MethodDeclaration;
use Microsoft\PhpParser\Node\QualifiedName;
use Microsoft\PhpParser\Node\Statement\ClassDeclaration;
use Microsoft\PhpParser\Node\Statement\ReturnStatement;
use Microsoft\PhpParser\Node\StringLiteral;
use Microsoft\PhpParser\Parser;
use Microsoft\PhpParser\Token;
use Microsoft\PhpParser\TokenKind;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Finder\SplFileInfo;

class AnnotateModel
{
    const STRING_TYPES = ['char', 'varchar', 'text'];
    const INT_TYPES = ['int', 'smallint', 'mediumint', 'bigint', 'tinyint'];
    const FLOAT_TYPES = ['decimal', 'float', 'double'];
    const DATE_TYPES = ['date', 'timestamp'];

    /** @var array */
    private $attributeNames;

    /** @var ClassDeclaration */
    private $classDeclaration;

    /** @var string */
    private $className;

    /** @var SplFileInfo */
    private $file;

    /** @var array */
    private $properties = [];

    /** @var Model */
    private $instance;

    /** @var Parser */
    private $parser;

    /** @var string */
    private $content;

    /** @var array */
    private $methodDeclarations;

    /** @var ReflectionClass */
    private $reflectionClass;

    /**
     * Debugging helper; prints some information about the node via print_r.
     *
     * @param Node|Token|null $node
     * @return void
     */
    public static function dumpText($node)
    {
        if ($node instanceof Node) {
            print_r($node->getText());
            print_r(' ;Node;  '.get_class($node));
            print_r("\n");
        } elseif ($node instanceof Token) {
            print_r($node->getText($this->content));
            print_r(' ;Token;  '.print_r($node->jsonSerialize(), true));
            print_r("\n");
        }
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

    public static function describeTable(Model $instance)
    {
        return $instance->getConnection()->select("DESCRIBE `{$instance->getTable()}`");
    }

    public static function relationshipMethods()
    {
        static $methods = null;

        if ($methods === null) {
            // extract names of eloquent relationship methods
            $reflectionClass = new ReflectionClass(HasRelationships::class);
            $methods = collect($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))
                ->map(function ($method) {
                    return $method->name;
                })->all();
        }

        return $methods;
    }

    private static function extractMethodDeclarations(ClassDeclaration $classDeclaration): array
    {
        $methods = [];
        $classMemberDeclarations = $classDeclaration->classMembers->classMemberDeclarations;
        foreach ($classMemberDeclarations as $declaration) {
            if ($declaration instanceof MethodDeclaration) {
                $methods[] = $declaration;
            }
        }

        return $methods;
    }

    private static function findClassDeclaration(Node $astNode): ?ClassDeclaration
    {
        foreach ($astNode->statementList as $statement) {
            if ($statement instanceof ClassDeclaration) {
                return $statement;
            }
        }

        return null;
    }

    public function __construct($class)
    {
        $this->reflectionClass = new ReflectionClass($class);
        $this->file = new SplFileInfo($this->reflectionClass->getFilename(), '', '');

        if (
            !$this->reflectionClass->isSubclassOf(Model::class) ||
            $this->reflectionClass->implementsInterface(HasDynamicTable::class)
        ) {
            return;
        }

        if (!$this->reflectionClass->isAbstract()) {
            $this->instance = $class::first() ?? new $class();
        }
    }

    public function addProperty(string $name, string $type, ?string $text = null)
    {
        $existingType = $this->properties[$name]['type'] ?? null;

        // override existing property if doesn't exist or mixed
        if ($existingType === null || $existingType === 'mixed') {
            $this->properties[$name] = ['type' => $type, 'text' => $text];
        }
    }

    public function annotate()
    {
        if ($this->isAnnotable()) {
            $this->parse();
            $this->extractProperties();
            $this->addAnnotationsToFile();
        }
    }

    public function extractProperties()
    {
        if ($this->instance !== null) {
            $this->findDirectProperties();
        }

        $this->findPropertiesFromMethods();
        $this->findPropertiesFromAttributes();

        ksort($this->properties);

        return $this->properties;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function isAnnotable()
    {
        return $this->reflectionClass->isSubclassOf(Model::class) && !$this->reflectionClass->implementsInterface(HasDynamicTable::class);
    }

    public function parse()
    {
        $this->content = $this->file->getContents();
        $parser = new Parser();
        $astNode = $parser->parseSourceFile($this->content);

        $this->classDeclaration = static::findClassDeclaration($astNode);
        $this->className = $this->classDeclaration->getNameParts()[0]->getText($this->content);
        $this->methodDeclarations = static::extractMethodDeclarations($this->classDeclaration);

        $this->attributeNames = collect($this->methodDeclarations)->filter(function ($item) {
            return ends_with($item->getName(), 'Attribute');
        })->map(function ($item) {
            $name = $item->getName();

            return snake_case(substr(substr($name, 3), 0, -9));
        })->all();
    }

    private function addAnnotationsToFile()
    {
        $node = $this->classDeclaration;

        if ($node === null) {
            echo "Could not find class declaration in {$this->file->getFilename()}!\n";

            return;
        }

        $existingComment = $node->getLeadingCommentAndWhitespaceText();
        $existingCommentLength = strlen($existingComment);

        $blockExists = starts_with(trim($existingComment), '/**') && ends_with(trim($existingComment), '*/');
        if (!$blockExists && empty($this->properties)) {
            echo "Nothing to annotate in {$this->file->getFilename()}\n";

            return;
        }

        if ($blockExists) {
            $lines = explode("\n", $existingComment);
            // remove every line with a @property and the block end
            $lines = array_filter($lines, function ($line) {
                return !(starts_with($line, ' * @property') || starts_with($line, ' */'));
            });

            // remove blank lines
            $lines = array_filter($lines, function ($line) {
                return strlen(trim($line)) > 0;
            });

            // reset indices
            $lines = array_values($lines);

            // figure out if there was an existing doc block that needs preserving
            $preserve = false;
            $numLines = count($lines);
            // first line should be '/**'
            if ($numLines > 1) {
                for ($i = 1; $i < $numLines; $i++) {
                    if (trim($lines[$i]) !== '*') {
                        $preserve = true;
                        break;
                    }
                }
            }

            // reset or add a spacer
            if (!$preserve) {
                $lines = ['/**'];
            } elseif (trim(array_last($lines)) !== '*') {
                $lines[] = ' *';
            }
        } else {
            $lines = ['/**'];
        }

        foreach ($this->properties as $name => $property) {
            $propertyText = " * @property {$property['type']} \${$name}";
            if (!empty($property['text'])) {
                $propertyText .= " {$property['text']}";
            }

            $lines[] = $propertyText;
        }

        $lines[] = ' */';
        $text = "\n\n".implode("\n", $lines)."\n";

        $newContent = substr_replace($this->content, $text, $node->getStartPosition() - $existingCommentLength, $existingCommentLength);
        File::put($this->file->getRealPath(), $newContent);
    }

    private function addPropertyFromCallExpression(string $name, CallExpression $expression)
    {
        [$function, $className] = $this->tryFindRelationshipFromCallExpression($expression);

        if ($className !== null) {
            $isCollection = in_array($function, static::collectionRelationshipMethods(), true);
            $this->addProperty(
                $name,
                // PSR-5 removed generics recently, so no standard typehint format for typed collections
                $isCollection ? '\Illuminate\Database\Eloquent\Collection' : $className,
                $isCollection ? $className : null
            );
        }
    }

    private function castType(string $field, string $type): string
    {
        $cast = $this->instance->getCasts()[$field] ?? null;
        if ($cast === 'boolean') {
            // why.jpg
            return 'bool';
        } elseif ($cast !== null) {
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

    private function findDirectProperties()
    {
        $columns = static::describeTable($this->instance);

        foreach ($columns as $column) {
            $columnAsArray = json_decode(json_encode($column), true); // This garbage is because StyleCI doesn't like ->Null.
            [$name, $type] = $this->parseColumn($columnAsArray);
            $this->addProperty($name, $type);
        }
    }

    private function findPropertiesFromAttributes()
    {
        foreach ($this->attributeNames as $name) {
            // TODO: should check the types and see if it needs to be overridden or not.
            $this->addProperty($name, 'mixed');
        }
    }

    private function findPropertiesFromMethods()
    {
        /** @var MethodDeclaration $declaration */
        foreach ($this->methodDeclarations as $declaration) {
            if ($this->tryExtractRelationship($declaration) !== null) {
                /** @var CompoundStatementNode|Token $statements */
                $statements = $declaration->compoundStatementOrSemicolon;
                // FIXME: check for abstract declaration instead.
                if ($statements instanceof Token) {
                    continue;
                }

                $statements = $statements->statements;

                // only consider methods with only single return statement for now.
                if (count($statements) !== 1) {
                    continue;
                }

                /** @var Node $statement */
                $statement = $statements[0];
                if (!$statement instanceof ReturnStatement) {
                    continue;
                }

                $nodes = $statement->getChildNodes();
                foreach ($nodes as $node) {
                    if ($node instanceof CallExpression) {
                        $this->addPropertyFromCallExpression($declaration->getName(), $node);
                    }
                }
            }
        }
    }

    // Field, Type, Null, Key, Default, Extra
    private function parseColumn($column)
    {
        $type = $this->parseType($column);

        return [$column['Field'], $type];
    }

    /**
     * Budget hacky type parser.
     *
     * @param string $type
     * @return string
     */
    private function parseType($column): string
    {
        $type = $this->castType($column['Field'], $column['Type']);

        if ($column['Null'] !== 'NO') {
            $type = $type.'|null';
        }

        return $type;
    }

    private function tryExtractRelationship(MethodDeclaration $declaration): ?MethodDeclaration
    {
        if (ends_with($declaration->getName(), 'Attribute') || $declaration->parameters !== null) {
            return null;
        }

        return $declaration;
    }

    private function tryFindRelationshipFromCallExpression(CallExpression $expression): ?array
    {
        $nodes = $expression->getDescendantNodes();

        foreach ($nodes as $node) {
            $relationship = $this->tryFindRelationshipFromNode($node);
            if ($relationship !== null) {
                return $relationship;
            }
        }

        return null;
    }

    private function tryFindRelationshipFromNode(Node $node): ?array
    {
        // keep going until probable relationship method.
        if (!$node instanceof MemberAccessExpression) {
            return null;
        }

        $children = iterator_to_array($node->getChildNodesAndTokens());
        $maybeThis = array_first($children);
        $maybeFunctionName = array_last($children);

        if (
            $maybeThis === null
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
        if (!$parent instanceof CallExpression) {
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

        $classNode = $argumentExpression->getFirstDescendantNode(QualifiedName::class, StringLiteral::class);
        if ($classNode === null) {
            return null;
        }

        $className = $classNode instanceof StringLiteral ? $classNode->getStringContentsText() : $classNode->getText();

        return [$functionName, $className];
    }
}
