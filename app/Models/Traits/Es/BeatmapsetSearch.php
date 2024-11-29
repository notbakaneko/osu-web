<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Models\Traits\Es;

use App\Models\Beatmap;
use Carbon\Carbon;
use Ds\Set;

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
                'tags' => $this->esTags(),
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
                $beatmapValues[$field] = $beatmap->$field;
            }

            // TODO: remove adding $beatmap->user_id once everything else also populated beatmap_owners by default.
            // Duplicate user_id in the array should be fine for now since the field isn't scored for querying.
            $beatmapValues['user_id'] = $beatmap->beatmapOwners->pluck('user_id')->add($beatmap->user_id);
            $values[] = $beatmapValues;

            if ($beatmap->playmode === Beatmap::MODES['osu']) {
                foreach (Beatmap::MODES as $modeInt) {
                    if ($modeInt === Beatmap::MODES['osu']) {
                        continue;
                    }

                    $convert = clone $beatmap;
                    $convert->playmode = $modeInt;
                    $convert->convert = true;
                    $convertValues = [];
                    foreach ($mappings as $field => $mapping) {
                        $convertValues[$field] = $convert->$field;
                    }

                    $convertValues['user_id'] = $beatmapValues['user_id']; // just add a copy for converts too.
                    $values[] = $convertValues;
                }
            }
        }

        return $values;
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

    private function esTags()
    {
        $tags = app('tags');
        $tagSet = new Set([$this->tags]);
        $beatmapTagNames = $this->beatmaps
            ->flatMap(fn (Beatmap $beatmap) => $beatmap->topTagIds())
            ->map(fn ($tagId) => $tags->get($tagId['tag_id'])?->name);

        $tagSet->add(...$beatmapTagNames);

        return $tagSet->toArray();
    }
}
