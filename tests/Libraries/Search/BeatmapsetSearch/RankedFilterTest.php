<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Tests\Libraries\Search\BeatmapsetSearch;

use App\Models\Beatmap;
use App\Models\Beatmapset;
use App\Models\User;
use DateTime;

class RankedFilterTest extends TestCase
{
    public static function dataProvider(): array
    {
        return [
            [[2], ['q' => 'ranked=2024']],
            [[2], ['q' => 'ranked>2023']],
            [[1, 2], ['q' => 'ranked>=2023']],
            [[0], ['q' => 'ranked<2023']],
            [[0, 1], ['q' => 'ranked<=2023']],

            [[0, 1], ['q' => '-ranked=2024']],
            [[0, 1], ['q' => '-ranked>2023']],
            [[0], ['q' => '-ranked>=2023']],
            [[1, 2], ['q' => '-ranked<2023']],
            [[2], ['q' => '-ranked<=2023']],
        ];
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::withDbAccess(function () {
            $factory = Beatmapset::factory()->withBeatmaps();
            static::$beatmapsets = [
                $factory->ranked(new DateTime('2022-02-25'))->state(['approved' => Beatmapset::STATES['approved']])->create(),
                $factory->ranked(new DateTime('2023-02-26'))->state(['approved' => Beatmapset::STATES['loved']])->create(),
                $factory->ranked(new DateTime('2024-02-27'))->create(),
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
