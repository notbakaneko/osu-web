<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Beatmap $beatmap
 * @property int $beatmap_id
 * @property \Carbon\Carbon $created_at
 * @property int $tag_id
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 * @property int $user_id
 */
class BeatmapTag extends Model
{
    public $incrementing = false;

    protected $primaryKey = ':composite';
    protected $primaryKeys = ['beatmap_id', 'tag_id', 'user_id'];

    public function scopeDefault(Builder $query): Builder
    {
        return $query->whereHas('user', fn ($userQuery) => $userQuery->default());
    }

    public function scopeTopTagIds(Builder $query)
    {
        return $query->default()
            ->groupBy('tag_id')
            ->select('tag_id')
            ->selectRaw('COUNT(*) as count')
            ->orderBy('count', 'desc')
            ->orderBy('tag_id', 'asc');
    }

    public function beatmap()
    {
        return $this->belongsTo(Beatmap::class, 'beatmap_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
