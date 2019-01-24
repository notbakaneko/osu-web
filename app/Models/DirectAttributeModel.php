<?php

/**
 *    Copyright 2015-2019 ppy Pty. Ltd.
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

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;

abstract class DirectAttributeModel extends Model
{
    public function getAttributeValue($key)
    {
        $value = $this->attributes[$key] ?? null;
        $method = 'get'.Str::studly($key).'Attribute';
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        if (array_key_exists($key, $this->getCasts())) {
            return $this->castAttribute($key, $value);
        }

        if (in_array($key, $this->getDates()) && !is_null($value)) {
            return $this->parseDate($value);
        }

        return $value;
    }

    // faster comparisons
    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        $type = $this->getCastType($key);
        switch ($type) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            case 'collection':
                return new BaseCollection($this->fromJson($value));
            case 'date':
                return $this->asDate($value);
            case 'datetime':
                return $this->asDateTime($value);
            case 'timestamp':
                return $this->asTimestamp($value);
            default:
                return $value;
        }
    }

    protected function getCastType($key)
    {
        return $this->getCasts()[$key];
    }

    protected function parseDate($value, $format = 'Y-m-d H:i:s')
    {
        if ($value !== null) {
            return Carbon::createFromFormat($format, $value);
        }
    }
}
