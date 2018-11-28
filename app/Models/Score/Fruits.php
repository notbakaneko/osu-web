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

namespace App\Models\Score;

/**
 *
 * @property int $score_id
 * @property mixed $scorechecksum
 * @property int $beatmap_id
 * @property int $beatmapset_id
 * @property int $user_id
 * @property int $score
 * @property int $maxcombo
 * @property mixed $rank
 * @property int $count50
 * @property int $count100
 * @property int $count300
 * @property int $countmiss
 * @property int $countgeki
 * @property int $countkatu
 * @property boolean $perfect
 * @property int $enabled_mods
 * @property int $pass
 * @property Carbon\Carbon $date
 * @property int|null $high_score_id
 */
class Fruits extends Model
{
    protected $table = 'osu_scores_fruits';
}
