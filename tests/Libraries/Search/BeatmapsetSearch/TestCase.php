<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Tests\Libraries\Search\BeatmapsetSearch;

use App\Libraries\Elasticsearch\Es;
use App\Libraries\Search\BeatmapsetSearch;
use App\Libraries\Search\BeatmapsetSearchRequestParams;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected static array $beatmapsets;

    public static function setUpBeforeClass(): void
    {;
        static::deleteAllEsBeatmapsets();
    }

    protected static function refresh(): void
    {
        Es::getClient()->indices()->refresh();
    }

    protected function setUp(): void
    {
        parent::setUp();
        config_set('osu.beatmapset.guest_advanced_search', true);
    }

    protected function searchAndAssert(array $expects, array $searchParams): void
    {
        $beatmapsetIds = array_map(fn (int $index) => static::$beatmapsets[$index]->getKey(), $expects);

        $this->assertEqualsCanonicalizing($beatmapsetIds, new BeatmapsetSearch(new BeatmapsetSearchRequestParams($searchParams))->response()->ids());
    }

    #[DataProvider('dataProvider')]
    public function testSearch(array $expected, array $params): void
    {
        $this->assertCount(count(static::$beatmapsets), new BeatmapsetSearch()->response()->ids());

        $this->searchAndAssert($expected, $params);
    }
}
