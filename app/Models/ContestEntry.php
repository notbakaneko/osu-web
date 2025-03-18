<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property-read Contest $contest
 * @property int $contest_id
 * @property \Carbon\Carbon|null $created_at
 * @property string|null $entry_url
 * @property string|null $thumbnail_url
 * @property int $id
 * @property-read Collection<ContestJudgeVote> $judgeVotes
 * @property string $masked_name
 * @property string $name
 * @property-read Collection<ContestJudgeScore> $scores
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @property int|null $user_id
 * @property-read Collection<ContestVote> $votes
 */
class ContestEntry extends Model
{
    public function scores(): HasManyThrough
    {
        return $this->hasManyThrough(ContestJudgeScore::class, ContestJudgeVote::class);
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function judgeVotes(): HasMany
    {
        return $this->hasMany(ContestJudgeVote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function votes()
    {
        return $this->hasMany(ContestVote::class);
    }

    public function scopeForBestOf(Builder $query, User $user, string $ruleset, ?string $variant = null): Builder
    {
        $query->whereIn('entry_url', function (QueryBuilder $beatmapsetQuery) use ($ruleset, $user, $variant) {
            $beatmapsetQuery->select('beatmapset_id')
                ->from('osu_beatmaps')
                ->where('osu_beatmaps.playmode', Beatmap::MODES[$ruleset])
                ->whereIn('beatmap_id', function (QueryBuilder $beatmapQuery) use ($user) {
                    $beatmapQuery->select('beatmap_id')
                        ->from('osu_user_beatmap_playcount')
                        ->where('user_id', $user->getKey());
                });

            if ($ruleset === 'mania' && $variant !== null) {
                if ($variant === 'nk') {
                    $beatmapsetQuery->whereNotIn('osu_beatmaps.diff_size', [4, 7]);
                } else {
                    $keys = match ($variant) {
                        '4k' => 4,
                        '7k' => 7,
                    };
                    $beatmapsetQuery->where('osu_beatmaps.diff_size', $keys);
                }
            }
        });

        return $query;
    }

    public function scopeWithScore(Builder $query, array $options): Builder
    {
        $orderValue = 'votes_count';

        if (isset($options['best_of'])) {
            $query
                ->selectRaw('*')
                ->selectRaw('(SELECT FLOOR(SUM(`weight`)) FROM `contest_votes` WHERE `contest_entries`.`id` = `contest_votes`.`contest_entry_id`) AS votes_count')
                ->limit(50); // best of contests tend to have a _lot_ of entries...
        } else if ($options['judged'] ?? false) {
            $query->withSum('scores', 'value');

            if ($options['is_score_standardised'] ?? false) {
                $scoreQuery = ContestJudgeVote::selectRaw(\DB::raw('SUM(`total_score_std`)'));
                $scoreQuery->whereColumn($scoreQuery->qualifyColumn('contest_entry_id'), $this->qualifyColumn('id'));
                $query->addSelect(['total_score_std' => $scoreQuery]);
                $orderValue = 'total_score_std';
            } else {
                $orderValue = 'scores_sum_value';
            }
        } else {
            $query->withCount('votes');
        }

        return $query->orderBy($orderValue, 'desc');
    }

    public function thumbnail(): ?string
    {
        if (!$this->contest->hasThumbnails()) {
            return null;
        }

        return presence($this->thumbnail_url) ?? $this->entry_url;
    }
}
