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

namespace App\Models\UserStatistics;

/**
 *
 * @property int $user_id
 * @property int $count300
 * @property int $count100
 * @property int $count50
 * @property int $countMiss
 * @property int $accuracy_total
 * @property int $accuracy_count
 * @property float $accuracy
 * @property int $playcount
 * @property int $ranked_score
 * @property int $total_score
 * @property int $x_rank_count
 * @property int $xh_rank_count
 * @property int $s_rank_count
 * @property int $sh_rank_count
 * @property int $a_rank_count
 * @property int $rank
 * @property float $level
 * @property int $replay_popularity
 * @property int $fail_count
 * @property int $exit_count
 * @property int $max_combo
 * @property string $country_acronym
 * @property float $rank_score
 * @property int $rank_score_index
 * @property float $accuracy_new
 * @property \Carbon\Carbon $last_update
 * @property \Carbon\Carbon $last_played
 */
class Taiko extends Model
{
    protected $table = 'osu_user_stats_taiko';
}
