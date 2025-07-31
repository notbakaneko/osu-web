<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace Tests\Libraries\Search;

use App\Libraries\Elasticsearch\Es;
use App\Libraries\Search\BeatmapsetQueryParser;
use App\Libraries\Search\BeatmapsetSearch;
use App\Libraries\Search\BeatmapsetSearchParams;
use App\Libraries\Search\BeatmapsetSearchRequestParams;
use App\Models\Beatmap;
use App\Models\Beatmapset;
use App\Models\BeatmapTag;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BeatmapsetSearchTest extends TestCase
{
    public function testManiaKeysFilter()
    {
        $beatmapsetFactory = Beatmapset::factory()->ranked();
        $beatmapFactory = Beatmap::factory()->ruleset('mania')->state(['approved' => Beatmapset::STATES['ranked']]);

        // TODO: map with 7 and 4 key
        $beatmapsets = [
            $beatmapsetFactory->withBeatmaps('osu')->create(),
            $beatmapsetFactory->has($beatmapFactory->state(['diff_size' => 7]))->create(),
            $beatmapsetFactory->has($beatmapFactory->state(['diff_size' => 4]))->create(),
            $beatmapsetFactory
                ->has($beatmapFactory->state(['diff_size' => 4]))
                ->has($beatmapFactory->state(['diff_size' => 7]))
                ->create(),
        ];
        $this->refresh();
        $this->assertCount(4, new BeatmapsetSearch()->response()->ids());

        $this->searchAndAssert([$beatmapsets[1], $beatmapsets[3]], ['q' => 'keys=7']);
        $this->searchAndAssert([$beatmapsets[2]], ['q' => '-keys=7']);
    }

    public function testTitleFilter()
    {
        $beatmapsetFactory = Beatmapset::factory()->ranked()->withBeatmaps();
        $beatmapsets = [
            $beatmapsetFactory->create(['title' => 'best']),
            $beatmapsetFactory->create(['title' => 'the best beatmap']),
            $beatmapsetFactory->create(['title_unicode' => 'the best beatmapよ']),
            $beatmapsetFactory->create(['artist' => 'the best artist']),
        ];
        $this->refresh();
        $this->assertCount(count($beatmapsets), new BeatmapsetSearch()->response()->ids());

        $this->searchAndAssert([$beatmapsets[0], $beatmapsets[1], $beatmapsets[2]], ['q' => "title=best"]);
        $this->searchAndAssert([$beatmapsets[3]], ['q' => "-title=best"]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        config_set('osu.beatmapset.guest_advanced_search', true);
        static::deleteAllEsBeatmapsets();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function refresh(): void
    {
        Es::getClient()->indices()->refresh();
    }

    private function searchAndAssert(array $expects, array $searchParams): void
    {
        $beatmapsetIds = array_map(fn (Beatmapset $beatmapset) => $beatmapset->getKey(), $expects);

        $this->assertEqualsCanonicalizing($beatmapsetIds, new BeatmapsetSearch(new BeatmapsetSearchRequestParams($searchParams))->response()->ids());
    }
}
