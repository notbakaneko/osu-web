<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Tests\Libraries\Search\BeatmapsetSearch;

use App\Models\Beatmap;
use App\Models\Beatmapset;
use App\Models\User;

class TitleFilterTest extends TestCase
{
    public static function dataProvider(): array
    {
        return [
            [[0, 1, 2], ['q' => 'title=best']],
            [[1, 2], ['q' => 'title="best beatmap"']],
            [[1, 2], ['q' => 'title="the beatmap"']],
            [[1, 2], ['q' => 'title=""best beatmap""']],
            [[], ['q' => 'title=""the beatmap""']],

            [[3], ['q' => '-title=best']],
            [[0, 3], ['q' => '-title="best beatmap"']],
            [[0, 3], ['q' => '-title="the beatmap"']],
            [[0, 3], ['q' => '-title=""best beatmap""']],
            [[0, 1, 2, 3], ['q' => '-title=""the beatmap""']],
        ];
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::withDbAccess(function () {
            $factory = Beatmapset::factory()->ranked()->withBeatmaps();
            static::$beatmapsets = [
                $factory->create(['title' => 'best']),
                $factory->create(['title' => 'the best beatmap']),
                $factory->create(['title_unicode' => 'the best beatmapよ']),
                $factory->create(['artist' => 'the best artist']),
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
