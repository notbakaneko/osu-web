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

use Cache;
use DB;

/**
 *
 * @property int $smiley_id
 * @property string $code
 * @property string $emotion
 * @property string $smiley_url
 * @property int $smiley_width
 * @property int $smiley_height
 * @property int $smiley_order
 * @property int $display_on_posting
 */
class Smiley extends Model
{
    protected $table = 'phpbb_smilies';

    public static function getAll()
    {
        return Cache::rememberForever('smilies', function () {
            return self::orderBy(DB::raw('LENGTH(code)'), 'desc')->get()->toArray();
        });
    }
}
