<?php

/**
 *    Copyright 2015-2017 ppy Pty. Ltd.
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

/**
 * @property \Illuminate\Database\Eloquent\Collection $artists Artist
 * @property \Carbon\Carbon|null $created_at
 * @property string $description
 * @property string $header_url
 * @property string $icon_url
 * @property int $id
 * @property string $name
 * @property string|null $soundcloud
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $website
 */
class Label extends Model
{
    public function artists()
    {
        return $this->hasMany(Artist::class);
    }
}
