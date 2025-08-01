<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Tests\Libraries\Search\BeatmapsetSearch;

use App\Models\Beatmap;
use App\Models\Beatmapset;
use App\Models\User;

class CreatorFilterTest extends TestCase
{
    public static function dataProvider(): array
    {
        return [
            'include lookup user' => [[0, 1], ['q' => 'creator=mapper']],
            'include non-lookup user' => [[0, 3], ['q' => 'creator=someone']],
            'exclude lookup user' => [[2, 3, 4], ['q' => '-creator=mapper']],
            'exclude non-lookup user' => [[1, 2, 4], ['q' => '-creator=someone']],
        ];
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::withDbAccess(function () {
            $factory = Beatmapset::factory()->ranked();
            $mapper1 = User::factory()->create(['username' => 'mapper']);
            $mapper2 = User::factory()->create(['username' => 'another_mapper']);

            static::$beatmapsets = [
                $factory->withBeatmaps()->withBeatmaps(guestMapper: $mapper1)->create(['creator' => 'someone']),
                $factory->withBeatmaps()->create(['user_id' => $mapper1]),
                $factory->withBeatmaps()->create(['user_id' => $mapper2]),
                $factory->withBeatmaps()->create(['creator' => 'someone_else']),
                $factory->withBeatmaps()->create(['creator' => 'unknown_mapper']),
            ];
            static::refresh();
        });
    }

    public static function tearDownAfterClass(): void
    {
        static::withDbAccess(function () {
            Beatmap::truncate();
            Beatmapset::truncate();
            User::truncate();
        });
    }
}
