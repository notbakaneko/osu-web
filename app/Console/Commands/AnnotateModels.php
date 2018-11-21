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

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Log;
use ReflectionClass;
use App\Libraries\HasDynamicTable;
use App\Models\Model;
use Symfony\Component\Finder\SplFileInfo;
use File;


class AnnotateModels extends Command
{
    const STRING_TYPES = ['char', 'varchar', 'text'];
    const INT_TYPES = ['int', 'smallint', 'mediumint', 'bigint', 'tinyint'];
    const FLOAT_TYPES = ['decimal', 'float', 'double'];
    const DATE_TYPES = ['date', 'timestamp'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annotate:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Annotates models';

    public static function describeTable(Model $instance)
    {
        $table = $instance->getTable();
        return $instance->getConnection()->select("DESCRIBE `{$table}`");
    }

    public static function getTables(string $connectionName = null)
    {
        return DB::connection($connectionName)->select('SHOW TABLES');
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = File::allFiles(app_path().'/Models');
        foreach ($files as $file) {
            $this->annotateFile($file);
        }
    }

    public function annotateFile(SplFileInfo $file)
    {
        $class = static::classFromFileInfo($file);
        $reflectionClass = new ReflectionClass($class);
        if (
            $reflectionClass->isAbstract() ||
            !$reflectionClass->isSubclassOf(Model::class) ||
            $reflectionClass->implementsInterface(HasDynamicTable::class)
        ) {
            return;
        }

        $properties = static::getClassAnnotations(new $class);

        static::addAnnotationsToFile($file, $properties);
    }

    public static function getClassAnnotations(Model $instance)
    {
        $columns = static::describeTable($instance);

        $properties = [];
        foreach ($columns as $column) {
            $properties[] = static::parseColumn($column);
        }

        // echo(print_r($properties, true));

        return $properties;
    }

    public static function addAnnotationsToFile(SplFileInfo $file, array $properties)
    {
        // $text = "\n\n{$file->getFilename()}\n";
        $text = "/**\n";
        foreach ($properties as $property) {
            $text .= " * @property {$property}\n";
        }
        $text .= " */\n";

        // echo($text);
        $content = $file->getContents();
        if (preg_match("/^class ([a-zA-Z]+) extends/m", $content, $matches)) {
            $newContent = str_replace_first($matches[0], $text.$matches[0], $content);
            File::put($file->getRealPath(), $newContent);
        } else {
            echo("No matches found in {$file->getFilename()}!\n");
        }
    }

    // Field, Type, Null, Key, Default, Extra
    public static function parseColumn($column)
    {
        $type = static::parseType($column);

        return "\${$column->Field} {$type}";
    }

    /**
     * Budget hacky type parser
     *
     * @param string $type
     * @return string
     */
    public static function parseType($column) : string
    {
        $type = static::castType($column->Type);

        if ($column->Null !== "NO") {
            $type = $type.'|null';
        }

        return $type;
    }

    // TODO: needs to also check against casting rules in model.
    public static function castType(string $type) : string
    {
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
}
