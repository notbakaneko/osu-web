<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace Tests\Libraries\Search;

use App\Libraries\Search\BeatmapsetSearch;
use App\Libraries\Search\UserSearch;
use App\Libraries\Search\UserSearchParams;
use App\Models\Artist;
use App\Models\ArtistTrack;
use App\Models\Beatmap;
use App\Models\Beatmapset;
use App\Models\User;
use Tests\TestCase;

class BeatmapsetSearchTest extends TestCase
{
    public function testFeaturedArtist()
    {
        $artist = Artist::factory()
            ->has(ArtistTrack::factory(), 'tracks')
            ->create();

        $tracks = $artist->tracks;
        $factory = Beatmapset::factory()->state(['approved' => 1])->withBeatmaps();
        foreach ($tracks as $track) {
            $factory->create(['track_id' => $track]);
        }

        new BeatmapsetSearch()->refresh();
        $this->assertSame(1, new BeatmapsetSearch()->records()->count());
    }

    protected function setUp(): void
    {
        parent::setUp();

        new BeatmapsetSearch()->deleteAll();
    }
}
