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
 *
 * @property int $achievement_id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property string|null $image
 * @property string $grouping
 * @property int $ordering
 * @property int $progression
 * @property int|null $quest_ordering
 * @property string|null $quest_instructions
 * @property boolean $enabled
 * @property int|null $mode
 * @property mixed $mode
 */
class Achievement extends Model
{
    protected $table = 'osu_achievements';
    protected $primaryKey = 'achievement_id';

    protected $casts = [
        'enabled' => 'boolean',
    ];
    public $timestamps = false;

    public function getModeAttribute($value)
    {
        if (!present($value)) {
            return;
        }

        return Beatmap::modeStr((int) $value);
    }

    public function scopeAchievable($query)
    {
        return $query
            ->where('enabled', true)
            ->where('slug', '<>', '');
    }
}
