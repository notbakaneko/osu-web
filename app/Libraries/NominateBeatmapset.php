<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace App\Libraries;

use App\Exceptions\InvariantException;
use App\Jobs\Notifications\BeatmapsetNominate;
use App\Models\Beatmapset;
use App\Models\BeatmapsetEvent;
use App\Models\User;

class NominateBeatmapset
{
    private bool $isLegacyNominationMode;

    /** @var string[] */
    private array $playmodesStr;


    public function __construct(private Beatmapset $beatmapset, private User $user, private array $playmodes)
    {
        $this->playmodesStr = $beatmapset->playmodesStr();
    }

    public function handle()
    {
        $this->beatmapset->resetMemoized(); // ensure we're not using cached/stale event data

        $this->assertValidState();

        $this->isLegacyNominationMode = $this->beatmapset->isLegacyNominationMode();

        if ($this->isLegacyNominationMode) {
            $this->nominateLegacy($this->user);
        } else {
            $playmodes = array_values(array_intersect($this->playmodesStr, $this->playmodes));

            if (empty($playmodes)) {
                throw new InvariantException(osu_trans('beatmapsets.nominate.hybrid_requires_modes'));
            }

            foreach ($playmodes as $mode) {
                if (!$this->user->isFullBN($mode) && !$this->user->isNAT($mode)) {
                    if (!$this->user->isLimitedBN($mode)) {
                        throw new InvariantException(osu_trans('beatmapsets.nominate.incorrect_mode', ['mode' => $mode]));
                    }

                    if ($this->beatmapset->requiresFullBNNomination($mode)) {
                        throw new InvariantException(osu_trans('beatmapsets.nominate.full_bn_required'));
                    }
                }
            }
        }

        $nomination = $this->beatmapset->beatmapsetNominations()->current()->where('user_id', $this->user->getKey());
        if (!$nomination->exists()) {
            $eventParams = [
                'type' => BeatmapsetEvent::NOMINATE,
                'user_id' => $this->user->getKey(),
            ];
            if (!$this->isLegacyNominationMode) {
                $eventParams['comment'] = ['modes' => $playmodes];
            }
            $event = $this->beatmapset->events()->create($eventParams);
            $this->beatmapset->beatmapsetNominations()->create([
                'event_id' => $event->getKey(),
                'modes' => $this->isLegacyNominationMode ? null : $playmodes,
                'user_id' => $this->user->getKey(),
            ]);

            if ($this->shouldQualify()) {
                $this->beatmapset->getConnection()->transaction(function () {
                    return $this->beatmapset->lockForUpdate()->find($this->beatmapset->getKey())->qualify($this->user);
                });
            } else {
                (new BeatmapsetNominate($this->beatmapset, $this->user))->dispatch();
            }
        }
    }

    private function assertValidState()
    {
        if (!$this->beatmapset->isPending()) {
            throw new InvariantException(osu_trans('beatmaps.nominations.incorrect_state'));
        }

        if ($this->beatmapset->hype < $this->beatmapset->requiredHype()) {
            throw new InvariantException(osu_trans('beatmaps.nominations.not_enough_hype'));
        }

        // check if there are any outstanding issues still
        if ($this->beatmapset->beatmapDiscussions()->openIssues()->count() > 0) {
            throw new InvariantException(osu_trans('beatmaps.nominations.unresolved_issues'));
        }
    }

    private function nominateLegacy(User $user)
    {
        // in legacy mode, we check if a user can nominate for _any_ of the beatmapset's playmodes
        $canNominate = false;
        $canFullNominate = false;

        foreach ($this->playmodesStr as $mode) {
            if ($user->isFullBN($mode) || $user->isNAT($mode)) {
                $canNominate = true;
                $canFullNominate = true;
            } else if ($user->isLimitedBN($mode)) {
                $canNominate = true;
            }
        }

        if (!$canNominate) {
            throw new InvariantException(osu_trans('beatmapsets.nominate.incorrect_mode', ['mode' => implode(', ', $this->playmodesStr)]));
        }

        if (!$canFullNominate && $this->beatmapset->requiresFullBNNomination()) {
            throw new InvariantException(osu_trans('beatmapsets.nominate.full_bn_required'));
        }
    }

    private function shouldQualify()
    {
        $currentNominations = $this->beatmapset->currentNominationCount();
        $requiredNominations = $this->beatmapset->requiredNominationCount();

        if ($this->isLegacyNominationMode) {
            return $currentNominations >= $requiredNominations;
        } else {
            $modesSatisfied = 0;
            foreach ($requiredNominations as $mode => $count) {
                if ($currentNominations[$mode] > $count) {
                    throw new InvariantException(osu_trans('beatmaps.nominations.too_many'));
                }

                if ($currentNominations[$mode] === $count) {
                    $modesSatisfied++;
                }
            }

            return $modesSatisfied >= $this->beatmapset->playmodeCount();
        }
    }
}
