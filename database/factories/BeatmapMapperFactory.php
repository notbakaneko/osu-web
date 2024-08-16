<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Beatmap;
use App\Models\BeatmapMapper;
use App\Models\User;

class BeatmapMapperFactory extends Factory
{
    protected $model = BeatmapMapper::class;

    public function definition(): array
    {
        return [
            'beatmap_id' => Beatmap::factory(),
            'user_id' => User::factory(),
        ];
    }
}
