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

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class CachingModel extends BaseModel
{
    public $_cache = [];

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!array_key_exists($key, $this->_cache)) {
            $this->_cache[$key] = parent::getAttribute($key);
        }

        return $this->_cache[$key];
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // FIXME: the problem with this is some mutators also modify the set value.
        // clearing the cache actually makes it slower because it has to be reloaded again on read.
        $this->_cache[$key] = $value;

        return parent::setAttribute($key, $value);
    }

    public function fresh($with = [])
    {
        $this->_cache = [];

        return parent::fresh($with);
    }

    public function refresh()
    {
        $this->_cache = [];

        return parent::refresh();
    }
}
