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
use App\Libraries\HasDynamicTable;
use App\Models\Model;
use Symfony\Component\Finder\SplFileInfo;
use File;

class AnnotateModel
{
    const STRING_TYPES = ['char', 'varchar', 'text'];
    const INT_TYPES = ['int', 'smallint', 'mediumint', 'bigint', 'tinyint'];
    const FLOAT_TYPES = ['decimal', 'float', 'double'];
    const DATE_TYPES = ['date', 'timestamp'];

    /** @var SplFileInfo */
    private $file;

    private $class;

    /** @var array */
    private $properties;

    /** @var Model */
    private $instance;

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

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;

        $class = static::classFromFileInfo($file);
    }

    public function getClassAnnotations()
    {
        $columns = static::describeTable($this->instance);

        $properties = [];
        foreach ($columns as $column) {
            $properties[] = $this->parseColumn($column);
        }

        // echo(print_r($properties, true));

        return $properties;
    }


    // Field, Type, Null, Key, Default, Extra
    public function parseColumn($column)
    {
        $type = $this->parseType($column);

        return "{$type} \${$column->Field}";
    }

    /**
     * Budget hacky type parser
     *
     * @param string $type
     * @return string
     */
    public function parseType($column) : string
    {
        $type = $this->castType($column->Field, $column->Type);

        if ($column->Null !== "NO") {
            $type = $type.'|null';
        }

        return $type;
    }

    public function castType(string $field, string $type) : string
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
            return 'Carbon\Carbon';
        }

        return 'mixed';
    }

    public function annotate()
    {
        $class = static::classFromFileInfo($this->file);
        $reflectionClass = new ReflectionClass($class);
        if (
            $reflectionClass->isAbstract() ||
            !$reflectionClass->isSubclassOf(Model::class) ||
            $reflectionClass->implementsInterface(HasDynamicTable::class)
        ) {
            return;
        }

        $this->instance = new $class;
        $this->properties = $this->getClassAnnotations();

        $this->addAnnotationsToFile();
    }

    public function addAnnotationsToFile()
    {
        // $text = "\n\n{$file->getFilename()}\n";
        $text = "/**\n";
        foreach ($this->properties as $property) {
            $text .= " * @property {$property}\n";
        }
        $text .= " */\n";

        // echo($text);
        $content = $this->file->getContents();
        if (preg_match("/^class ([a-zA-Z]+) extends/m", $content, $matches)) {
            $newContent = str_replace_first($matches[0], $text.$matches[0], $content);
            File::put($this->file->getRealPath(), $newContent);
        } else {
            echo("No matches found in {$this->file->getFilename()}!\n");
        }
    }
}
