<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

declare(strict_types=1);

namespace App\Libraries\Opengraph\Solo;

use App\Libraries\Opengraph\OpengraphInterface;
use App\Models\Solo\Score;

class ScoreOpengraph implements OpengraphInterface
{
    private string $username;

    public function __construct(private Score $score)
    {
        $this->username = $this->score->user === null || $this->score->user->trashed() ? osu_trans('users.deleted') : $this->score->user->username;
    }

    public function get(): array
    {
        return [
            'description' => $this->score->beatmap->beatmapset->getDisplayTitle(null).' '.$this->score->beatmap->version,
            'image' => $this->score->beatmap->beatmapset->coverURL('slimcover'),
            'title' => $this->getTitle(),
        ];
    }

    private function getTitle(): string
    {
        return "{$this->username}: {$this->score->pp}pp";
    }
}
