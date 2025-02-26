<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Models\Traits\Es;

use App\Models\Beatmap;
use Carbon\Carbon;

trait BeatmapsetSearch
{
    use BaseDbIndexable;

    public static function esIndexName()
    {
        return $GLOBALS['cfg']['osu']['elasticsearch']['prefix'].'beatmaps';
    }

    public static function esIndexingQuery()
    {
        return static::withoutGlobalScopes()
            ->active()
            ->with('beatmaps') // note that the with query will run with the default scopes.
            ->with('beatmaps.beatmapOwners')
            ->with('beatmaps.baseDifficultyRatings');
    }

    public static function esSchemaFile()
    {
        return config_path('schemas/beatmapsets.json');
    }

    public function esShouldIndex()
    {
        return !$this->trashed() && !present($this->download_disabled_url);
    }

    public function toEsJson()
    {
        return array_merge(
            $this->esBeatmapsetValues(),
            ['beatmaps' => $this->esBeatmapsValues()],
            ['difficulties' => $this->esDifficultiesValues()]
        );
    }

    private function esBeatmapsetValues()
    {
        $mappings = static::esMappings();

        $values = [];
        foreach ($mappings as $field => $mapping) {
            if ($field === 'beatmaps' || $field === 'difficulties') {
                continue;
            }

            $value = match ($field) {
                'id' => $this->getKey(),
                default => $this->$field,
            };

            if ($value instanceof Carbon) {
                $value = $value->toIso8601String();
            }

            $values[$field] = $value;
        }

        return $values;
    }

    private function esBeatmapsValues()
    {
        $mappings = static::esMappings()['beatmaps']['properties'];

        $values = [];
        foreach ($this->beatmaps as $beatmap) {
            $beatmapValues = [];
            foreach ($mappings as $field => $mapping) {
                $value = match ($field) {
                    'top_tags' => $this->esBeatmapTags($beatmap),
                    // TODO: remove adding $beatmap->user_id once everything else also populated beatmap_owners by default.
                    // Duplicate user_id in the array should be fine for now since the field isn't scored for querying.
                    'user_id' => $beatmap->beatmapOwners->pluck('user_id')->add($beatmap->user_id),
                    default => $beatmap->$field,
                };

                $beatmapValues[$field] = $value;
            }

            $values[] = $beatmapValues;

            if ($beatmap->playmode === Beatmap::MODES['osu']) {
                foreach (Beatmap::MODES as $modeInt) {
                    if ($modeInt === Beatmap::MODES['osu']) {
                        continue;
                    }

                    $values[] = [
                        ...$beatmapValues,
                        'convert' => true,
                        'playmode' => $modeInt,
                    ];
                }
            }
        }

        return $values;
    }

    private function esBeatmapTags(Beatmap $beatmap): array
    {
        $tags = app('tags');

        return array_filter(
            array_map(
                fn ($tagId) => $tags->get($tagId['tag_id'])?->name,
                $beatmap->topTagIds()
            )
        );
    }

    private function esDifficultiesValues()
    {
        $mappings = static::esMappings()['difficulties']['properties'];

        $values = [];
        // initialize everything to an array.
        foreach ($mappings as $field => $mapping) {
            $values[$field] = [];
        }

        foreach ($this->beatmaps as $beatmap) {
            foreach ($mappings as $field => $mapping) {
                $values[$field][] = $beatmap[$field];
            }
        }

        return $values;
    }
}
