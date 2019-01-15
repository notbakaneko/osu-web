<?php

namespace App\Models;

/**
 *
 * @property int $user_id
 * @property int $beatmapset_id
 * @property int $rating
 * @property \Carbon\Carbon $date
 * @property Beatmapset $beatmapset
 * @property User $user
 */
class BeatmapsetUserRating extends Model
{
    protected $table = 'osu_user_beatmapset_ratings';

    public $timestamps = false;

    public function beatmapset()
    {
        return $this->belongsTo(Beatmapset::class, 'beatmapset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
