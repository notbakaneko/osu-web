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
        $method = 'get'.Str::studly($key).'Attribute';
        if (method_exists($this, $method)) {
            return $this->$method($this->attributes[$key] ?? null);
        }

        return $this->attributes[$key] ?? null;
    }

    protected function parseDate($value, $format = 'Y-m-d H:i:s')
    {
        if ($value !== null) {
            return Carbon::createFromFormat($format, $value);
        }
    }
}
