<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace Tests\Models;

use App\Enums\Ruleset;
use App\Exceptions\AuthorizationException;
use App\Exceptions\InvariantException;
use App\Jobs\CheckBeatmapsetCovers;
use App\Jobs\Notifications\BeatmapsetDisqualify;
use App\Jobs\Notifications\BeatmapsetResetNominations;
use App\Libraries\NominateBeatmapset;
use App\Models\Beatmap;
use App\Models\BeatmapDiscussion;
use App\Models\Beatmapset;
use App\Models\BeatmapsetNomination;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Bus;
use Database\Factories\BeatmapsetFactory;
use Queue;
use Tests\TestCase;

class BeatmapsetTest extends TestCase
{
    public function testLove()
    {
        $user = User::factory()->create();
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps()->create();

        $notifications = Notification::count();
        $userNotifications = UserNotification::count();

        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => $beatmapset->bssProcessQueues()->count(), 1);

        $beatmapset->love($user);

        $this->assertSame($notifications + 1, Notification::count());
        $this->assertSame($userNotifications + 1, UserNotification::count());
        $this->assertTrue($beatmapset->fresh()->isLoved());
        $this->assertSame('loved', $beatmapset->beatmaps()->first()->status());

        Bus::assertDispatched(CheckBeatmapsetCovers::class);
    }

    public function testLoveBeatmapApprovedStates(): void
    {
        $user = User::factory()->create();
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps()->create();

        $specifiedBeatmap = $beatmapset->beatmaps()->first();
        $beatmapset->beatmaps()->saveMany([
            $graveyardBeatmap = Beatmap::factory()->make(['approved' => Beatmapset::STATES['graveyard']]),
            $pendingBeatmap = Beatmap::factory()->make(['approved' => Beatmapset::STATES['pending']]),
            $wipBeatmap = Beatmap::factory()->make(['approved' => Beatmapset::STATES['wip']]),
            $rankedBeatmap = Beatmap::factory()->make(['approved' => Beatmapset::STATES['ranked']]),
        ]);

        $beatmapset->love($user, [$specifiedBeatmap->getKey()]);

        $this->assertTrue($beatmapset->fresh()->isLoved());
        $this->assertSame('loved', $specifiedBeatmap->fresh()->status());
        $this->assertSame('graveyard', $graveyardBeatmap->fresh()->status());
        $this->assertSame('graveyard', $pendingBeatmap->fresh()->status());
        $this->assertSame('graveyard', $wipBeatmap->fresh()->status());
        $this->assertSame('ranked', $rankedBeatmap->fresh()->status());

        Bus::assertDispatched(CheckBeatmapsetCovers::class);
    }

    public function testMainRulesetSingleBeatmap()
    {
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps(Ruleset::taiko)->create();

        $this->assertSame(Ruleset::taiko, $beatmapset->mainRuleset());
    }

    public function testMainRulesetHybridBeatmapset()
    {
        $beatmapset = $this->beatmapsetFactory()
            ->withBeatmaps(Ruleset::osu, 1)
            ->withBeatmaps(Ruleset::taiko, 2)
            ->withBeatmaps(Ruleset::catch, 3)
            ->withBeatmaps(Ruleset::mania, 1)
            ->create();

        $this->assertSame(Ruleset::catch, $beatmapset->mainRuleset());
    }

    public function testMainRulesetHybridBeatmapsetWithGuestMappers()
    {
        $guest = User::factory()->create();

        $beatmapset = $this->beatmapsetFactory()
            ->withBeatmaps(Ruleset::osu, 1, $guest)
            ->withBeatmaps(Ruleset::taiko, 3, $guest)
            ->withBeatmaps(Ruleset::taiko, 1)
            ->withBeatmaps(Ruleset::catch, 2, $guest)
            ->withBeatmaps(Ruleset::catch, 2)
            ->withBeatmaps(Ruleset::mania, 1)
            ->create();

        $this->assertSame(Ruleset::catch, $beatmapset->mainRuleset());
    }

    public function testMainRulesetHybridBeatmapsetWithGuestMappersSameCount()
    {
        $guest = User::factory()->create();

        $beatmapset = $this->beatmapsetFactory()
            ->withBeatmaps(Ruleset::osu, 1)
            ->withBeatmaps(Ruleset::taiko, 1, $guest)
            ->withBeatmaps(Ruleset::taiko, 1)
            ->withBeatmaps(Ruleset::catch, 2, $guest)
            ->withBeatmaps(Ruleset::catch, 2)
            ->withBeatmaps(Ruleset::mania, 2, $guest)
            ->withBeatmaps(Ruleset::mania, 2)
            ->create();

        $this->assertSame(Ruleset::catch, $beatmapset->mainRuleset());
    }

    //region single-playmode beatmap sets

    /**
     * @dataProvider nominateDataProvider
     */
    public function testNominate(string $group, array $groupPlaymodes, Ruleset $ruleset)
    {
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps($ruleset)->create();
        $user = User::factory()->withGroup($group, $groupPlaymodes)->create();
        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 1);
        $this->expectCountChange(fn () => UserNotification::count(), 1);
        $this->expectCountChange(fn () => $beatmapset->nominations, 1);
        $this->expectCountChange(fn () => $beatmapset->beatmapsetNominations()->current()->count(), 1);

        (new NominateBeatmapset($beatmapset, $user, [$ruleset->legacyName()]))->handle();

        $this->assertTrue($beatmapset->isPending());
    }

    public static function nominateDataProvider()
    {
        return [
            'bng nominate' => ['bng', ['osu'], Ruleset::osu],
            'nat defaults to all rulesets' => ['nat', [], Ruleset::osu],
        ];
    }

    public function testQualify()
    {
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps()->create();
        $user = User::factory()->withGroup('bng', $beatmapset->playmodesStr())->create();
        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => $beatmapset->bssProcessQueues()->count(), 1);
        $this->expectCountChange(fn () => Notification::count(), 1);
        $this->expectCountChange(fn () => UserNotification::count(), 1);

        $beatmapset->qualify($user);

        $this->assertTrue($beatmapset->fresh()->isQualified());

        Bus::assertDispatched(CheckBeatmapsetCovers::class);
    }

    public function testNominateWithDefaultMetadata()
    {
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps()->state([
            'genre_id' => Genre::UNSPECIFIED,
            'language_id' => Language::UNSPECIFIED,
        ])->create();
        $nominator = User::factory()->withGroup('bng', $beatmapset->playmodesStr())->create();

        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(osu_trans('authorization.beatmap_discussion.nominate.set_metadata'));
        priv_check_user($nominator, 'BeatmapsetNominate', $beatmapset)->ensureCan();
    }

    /**
     * @dataProvider qualifyingNominationsDataProvider
     */
    public function testQualifyingNominations(string $initialGroup, string $qualifyingGroup, bool $expected)
    {
        /** @var Ruleset */
        $ruleset = array_rand_val(Ruleset::cases());
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps($ruleset)->create();
        $this->fillNominationsExceptLastForMainRuleset($beatmapset, $initialGroup);

        $nominator = User::factory()->withGroup($qualifyingGroup, [$ruleset->legacyName()])->create();

        $this->assertFalse($beatmapset->isQualified());

        priv_check_user($nominator, 'BeatmapsetNominate', $beatmapset)->ensureCan();

        if (!$expected) {
            $this->expectException(InvariantException::class);
        }

        (new NominateBeatmapset($beatmapset, $nominator, [$ruleset->legacyName()]))->handle();

        $this->assertSame($expected, $beatmapset->isQualified());

        if ($expected) {
            Bus::assertDispatched(CheckBeatmapsetCovers::class);
        } else {
            Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
        }
    }

    /**
     * @dataProvider dataProviderForTestRank
     */
    public function testRank(string $state, bool $success): void
    {
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps()->$state()->create();
        $otherUser = User::factory()->create();
        $beatmap = $beatmapset->beatmaps()->first();
        $beatmap->scoresBest()->create([
            'user_id' => $otherUser->getKey(),
        ]);

        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => $beatmapset->bssProcessQueues()->count(), $success ? 1 : 0);
        $this->expectCountChange(fn () => UserNotification::count(), $success ? 1 : 0);
        $this->expectCountChange(fn () => Notification::count(), $success ? 1 : 0);
        $this->expectCountChange(fn () => $beatmap->scoresBest()->count(), $success ? -1 : 0);

        $res = $beatmapset->rank();

        $this->assertSame($success, $res);
        $this->assertSame($success, $beatmapset->fresh()->isRanked());

        if ($success) {
            Bus::assertDispatched(CheckBeatmapsetCovers::class);
        } else {
            Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
        }
    }

    /**
     * @dataProvider rankWithOpenIssueDataProvider
     */
    public function testRankWithOpenIssue(string $type): void
    {
        $beatmapset = $this->beatmapsetFactory()->withBeatmaps()
            ->qualified()
            ->has(BeatmapDiscussion::factory()->general()->messageType($type))->create();

        $this->assertTrue($beatmapset->isQualified());
        $this->assertFalse($beatmapset->rank());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testGlobalScopeActive()
    {
        $beatmapset = Beatmapset::factory()->inactive()->create();
        $id = $beatmapset->getKey();

        $this->assertNull(Beatmapset::find($id)); // global scope
        $this->assertNull(Beatmapset::withoutGlobalScopes()->active()->find($id)); // scope still applies after removing global scope
        $this->assertTrue($beatmapset->is(Beatmapset::withoutGlobalScopes()->find($id))); // no global scopes
    }

    public function testGlobalScopeSoftDelete()
    {
        $beatmapset = Beatmapset::factory()->inactive()->deleted()->create();
        $id = $beatmapset->getKey();

        $this->assertNull(Beatmapset::withTrashed()->find($id));
    }

    //endregion

    //region multi-playmode beatmap sets (aka hybrid)
    public function testHybridLegacyNominate(): void
    {
        $user = User::factory()->withGroup('bng', ['osu'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        // create legacy nomination event to enable legacy nomination mode
        BeatmapsetNomination::factory()->create([
            'beatmapset_id' => $beatmapset,
            'user_id' => User::factory()->withGroup('bng', $beatmapset->playmodesStr()),
        ]);

        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 1);
        $this->expectCountChange(fn () => UserNotification::count(), 1);

        $result = $beatmapset->nominate($user);

        $this->assertTrue($result['result']);
        $this->assertTrue($beatmapset->fresh()->isPending());
    }

    public function testHybridLegacyQualify(): void
    {
        $user = User::factory()->withGroup('bng', ['osu'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        // create legacy nomination event to enable legacy nomination mode
        BeatmapsetNomination::factory()->create([
            'beatmapset_id' => $beatmapset,
            'user_id' => User::factory()->withGroup('bng', $beatmapset->playmodesStr()),
        ]);

        // fill with legacy nominations
        $count = $beatmapset->requiredNominationCount() - $beatmapset->currentNominationCount() - 1;
        for ($i = 0; $i < $count; $i++) {
            $beatmapset->nominate(User::factory()->withGroup('bng', ['osu'])->create());
        }

        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 1);
        $this->expectCountChange(fn () => UserNotification::count(), 1);

        $result = $beatmapset->nominate($user);

        $this->assertTrue($result['result']);
        $this->assertTrue($beatmapset->fresh()->isQualified());

        Bus::assertDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominateWithNullPlaymode(): void
    {
        $user = User::factory()->create();
        $beatmapset = $this->createHybridBeatmapset();
        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 0);
        $this->expectCountChange(fn () => UserNotification::count(), 0);

        $result = $beatmapset->nominate($user);

        $this->assertFalse($result['result']);
        $this->assertSame($result['message'], osu_trans('beatmapsets.nominate.hybrid_requires_modes'));

        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominateWithNoPlaymodePermission(): void
    {
        $user = User::factory()->withGroup('bng', ['osu'])->create();
        $beatmapset = $this->createHybridBeatmapset();
        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 0);
        $this->expectCountChange(fn () => UserNotification::count(), 0);

        $result = $beatmapset->nominate($user, ['taiko']);

        $this->assertFalse($result['result']);
        $this->assertSame($result['message'], osu_trans('beatmapsets.nominate.incorrect_mode', ['mode' => 'taiko']));

        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominateWithPlaymodePermissionSingleMode(): void
    {
        $user = User::factory()->withGroup('bng', ['osu'])->create();
        $beatmapset = $this->createHybridBeatmapset();
        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 1);
        $this->expectCountChange(fn () => UserNotification::count(), 1);

        $result = $beatmapset->nominate($user, ['osu']);

        $this->assertTrue($result['result']);
        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominateWithPlaymodePermissionTooMany(): void
    {
        $user = User::factory()->withGroup('bng', ['osu'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        $this->fillNominationsExceptLastForMainRuleset($beatmapset, 'bng');

        $result = $beatmapset->nominate(User::factory()->withGroup('bng', ['osu'])->create(), ['osu']);
        $this->assertTrue($result['result']);

        $result = $beatmapset->fresh()->nominate($user, ['osu']);

        $this->assertFalse($result['result']);
        $this->assertSame($result['message'], osu_trans('beatmaps.nominations.too_many'));
        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominateWithPlaymodePermissionMultipleModes(): void
    {
        $user = User::factory()->withGroup('bng', ['osu', 'taiko'])->create();
        $beatmapset = $this->createHybridBeatmapset();
        $otherUser = User::factory()->create();
        $beatmapset->watches()->create(['user_id' => $otherUser->getKey()]);

        $this->expectCountChange(fn () => Notification::count(), 1);
        $this->expectCountChange(fn () => UserNotification::count(), 1);

        $result = $beatmapset->nominate($user, ['osu', 'taiko']);

        $this->assertTrue($result['result']);
        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominationBNGQualifyingBNGNominatedPartial(): void
    {
        $user = User::factory()->withGroup('bng_limited', ['osu', 'taiko'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        $this->fillNominationsExceptLastForMainRuleset($beatmapset, 'bng');

        $result = $beatmapset->nominate($user, ['osu']);

        $this->assertTrue($result['result']);
        $this->assertFalse($beatmapset->fresh()->isQualified());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominationLimitedBNGQualifyingLimitedBNGNominated(): void
    {
        $user = User::factory()->withGroup('bng_limited', ['osu', 'taiko'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        $this->fillNominationsExceptLastForMainRuleset($beatmapset, 'bng_limited');

        $result = $beatmapset->fresh()->nominate($user, ['osu', 'taiko']);

        $this->assertFalse($result['result']);
        $this->assertSame($result['message'], osu_trans('beatmapsets.nominate.full_bn_required'));
        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominationLimitedBNGQualifyingBNGNominated(): void
    {
        $user = User::factory()->withGroup('bng', ['osu', 'taiko'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        $this->fillNominationsExceptLastForMainRuleset($beatmapset, 'bng_limited');

        $result = $beatmapset->nominate($user, ['osu', 'taiko']);

        $this->assertTrue($result['result']);
        $this->assertTrue($beatmapset->fresh()->isQualified());

        Bus::assertDispatched(CheckBeatmapsetCovers::class);
    }

    public function testHybridNominationBNGQualifyingLimitedBNGNominated(): void
    {
        $user = User::factory()->withGroup('bng_limited', ['osu', 'taiko'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        $this->fillNominationsExceptLastForMainRuleset($beatmapset, 'bng');

        $result = $beatmapset->nominate($user, ['osu', 'taiko']);

        $this->assertFalse($result['result']);
        $this->assertTrue($beatmapset->fresh()->isPending());

        Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
    }

    /**
     * @dataProvider qualifyingNominationsHybridDataProvider
     */
    public function testQualifyingNominationsHybrid(string $initialGroup, string $qualifyingGroup, bool $expected)
    {
        $nominator = User::factory()->withGroup($qualifyingGroup, ['osu', 'taiko'])->create();
        $beatmapset = $this->createHybridBeatmapset();

        $this->fillNominationsExceptLastForMainRuleset($beatmapset, $initialGroup);

        $beatmapset->refresh();
        $this->assertFalse($beatmapset->isQualified());

        priv_check_user($nominator, 'BeatmapsetNominate', $beatmapset)->ensureCan();

        if (!$expected) {
            $this->expectException(InvariantException::class);
        }

        (new NominateBeatmapset($beatmapset, $nominator, ['osu', 'taiko']))->handle();

        $this->assertSame($expected, $beatmapset->isQualified());

        if ($expected) {
            Bus::assertDispatched(CheckBeatmapsetCovers::class);
        } else {
            Bus::assertNotDispatched(CheckBeatmapsetCovers::class);
        }
    }

    //endregion

    //region disqualification

    /**
     * @dataProvider disqualifyOrResetNominationsDataProvider
     */
    public function testDisqualifyOrResetNominations(string $state, string $pushed)
    {
        $user = User::factory()->withGroup('bng')->create();
        $beatmapset = Beatmapset::factory()->owner()->withDiscussion()->$state()->create();
        $discussion = $beatmapset->beatmapDiscussions()->first(); // contents only needed for logging.

        Queue::fake();

        $beatmapset->disqualifyOrResetNominations($user, $discussion);

        Queue::assertPushed($pushed);
    }

    //endregion

    public static function disqualifyOrResetNominationsDataProvider()
    {
        return [
            ['pending', BeatmapsetResetNominations::class],
            ['qualified', BeatmapsetDisqualify::class],
        ];
    }

    public static function dataProviderForTestRank(): array
    {
        return [
            ['pending', false],
            ['qualified', true],
        ];
    }

    public static function qualifyingNominationsDataProvider(): array
    {
        // existing nominations, qualifying nomination, expected
        return [
            'Nomination requires at least one full nominator' => ['bng_limited', 'bng_limited', false],

            // limited bngs can be the qualifying nomination
            ['bng', 'bng_limited', true],
            ['nat', 'bng_limited', true],

            ['bng_limited', 'bng', true],
            ['bng_limited', 'nat', true],
        ];
    }

    public static function qualifyingNominationsHybridDataProvider(): array
    {
        // existing nominations, qualifying nomination, expected
        return [
            'Nomination requires at least one full nominator' => ['bng_limited', 'bng_limited', false],
            'Limited BNs cannot nominate the hybrid mode #1' => ['bng', 'bng_limited', false],
            'Limited BNs cannot nominate the hybrid mode #2' => ['nat', 'bng_limited', false],

            ['bng_limited', 'bng', true],
            ['bng_limited', 'nat', true],
        ];
    }

    public static function rankWithOpenIssueDataProvider()
    {
        return [
            ['problem'],
            ['suggestion'],
        ];
    }

    private function beatmapsetFactory(): BeatmapsetFactory
    {
        return Beatmapset::factory()->owner()->pending();
    }

    private function createHybridBeatmapset($rulesets = [Ruleset::osu, Ruleset::taiko]): Beatmapset
    {
        $beatmapset = $this->beatmapsetFactory();

        foreach ($rulesets as $ruleset) {
            $beatmapset = $beatmapset->withBeatmaps($ruleset);
        }

        return $beatmapset->create();
    }

    private function fillNominationsExceptLastForMainRuleset(Beatmapset $beatmapset, string $group): void
    {
        $mode = $beatmapset->mainRuleset()->legacyName();
        $count = $beatmapset->requiredNominationCount()[$mode] - 1;
        for ($i = 0; $i < $count; $i++) {
            $beatmapset->nominate(User::factory()->withGroup($group, [$mode])->create(), [$mode]);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        Genre::factory()->create(['genre_id' => Genre::UNSPECIFIED]);
        Language::factory()->create(['language_id' => Language::UNSPECIFIED]);

        Bus::fake([CheckBeatmapsetCovers::class]);
    }
}
