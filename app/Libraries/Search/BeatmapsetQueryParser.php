<?php

// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

namespace App\Libraries\Search;

use App\Models\Beatmapset;
use Carbon\CarbonImmutable;

class BeatmapsetQueryParser
{
    public static function parse(?string $query): array
    {
        $options = [];

        // reference: https://github.com/ppy/osu/blob/f6baf49ad6b42c662a729ad05e18bd99bc48b4c7/osu.Game/Screens/Select/FilterQueryParser.cs
        // adjusted for multiple quoted options (with side effect of inner quotes must be escaped)
        $keywords = preg_replace_callback('#\b(?<key>\w+)(?<op>(:|=|(>|<)(:|=)?))(?<value>("{1,2})(?:\\\"|.)*?\7|\S*)#i', function ($m) use (&$options) {
            $key = strtolower($m['key']);
            $op = str_replace(':', '=', $m['op']);
            switch ($key) {
                case 'star':
                case 'stars':
                    $key = 'stars';
                    $option = static::makeFloatRangeOption($op, $m['value'], 0.01 / 2);
                    break;
                case 'ar':
                    $option = static::makeFloatRangeOption($op, $m['value'], 0.1 / 2);
                    break;
                case 'dr':
                case 'hp':
                    $key = 'dr';
                    $option = static::makeFloatRangeOption($op, $m['value'], 0.1 / 2);
                    break;
                case 'cs':
                    $option = static::makeFloatRangeOption($op, $m['value'], 0.1 / 2);
                    break;
                case 'od':
                    $option = static::makeFloatRangeOption($op, $m['value'], 0.1 / 2);
                    break;
                case 'bpm':
                    $option = static::makeFloatRangeOption($op, $m['value'], 0.01 / 2);
                    break;
                case 'circles':
                    $option = static::makeIntRangeOption($op, $m['value']);
                    break;
                case 'sliders':
                    $option = static::makeIntRangeOption($op, $m['value']);
                    break;
                case 'length':
                    $parsed = get_length_seconds($m['value']);
                    if ($parsed !== null) {
                        $option = static::makeFloatRangeOption($op, $parsed['value'], $parsed['min_scale'] / 2);
                    }
                    break;
                case 'featured_artist':
                    $option = static::makeIntOption($op, $m['value']);
                    break;
                case 'key':
                case 'keys':
                    $key = 'keys';
                    $option = static::makeIntRangeOption($op, $m['value']);
                    break;
                case 'divisor':
                    $option = static::makeIntRangeOption($op, $m['value']);
                    break;
                case 'status':
                    $option = static::makeIntRangeOption($op, static::statePrefixSearch($m['value']));
                    break;
                case 'creator':
                    $option = static::makeTextOption($op, $m['value']);
                    break;
                case 'difficulty':
                    $option = static::makeTextOption($op, $m['value']);
                    break;
                case 'favourites':
                    $option = static::makeIntRangeOption($op, $m['value']);
                    break;
                case 'artist':
                    $option = static::makeTextOption($op, $m['value']);
                    break;
                case 'source':
                    $option = static::makeTextOption($op, $m['value']);
                    break;
                case 'tag':
                    $option = [static::makeTextOption($op, $m['value'])];
                    break;
                case 'title':
                    $option = static::makeTextOption($op, $m['value']);
                    break;
                case 'created':
                    $option = static::makeDateRangeOption($op, $m['value']);
                    break;
                case 'ranked':
                    $option = static::makeDateRangeOption($op, $m['value']);
                    break;
                case 'updated':
                    $option = static::makeDateRangeOption($op, $m['value']);
                    break;
            }

            if (isset($option)) {
                if (is_array($option)) {
                    $options[$key] = array_merge($options[$key] ?? [], $option);
                } else {
                    $options[$key] = $option;
                }

                return '';
            }

            return $m[0];
        }, $query ?? '');

        return [
            'keywords' => presence(trim($keywords)),
            'options' => $options,
        ];
    }

    private static function makeDateRangeOption(string $operator, string $value): ?array
    {
        $value = presence(trim($value, '"'));

        if (preg_match('#^\d{4}$#', $value) === 1) {
            $startTime = CarbonImmutable::create($value, 1, 1, 0, 0, 0, 'UTC');
            $endTime = $startTime->addYears(1);
        } elseif (preg_match('#^(?<year>\d{4})[-./]?(?<month>\d{1,2})$#', $value, $m) === 1) {
            $startTime = CarbonImmutable::create($m['year'], $m['month'], 1, 0, 0, 0, 'UTC');
            $endTime = $startTime->addMonths(1);
        } elseif (preg_match('#^(?<year>\d{4})[-./]?(?<month>\d{1,2})[-./]?(?<day>\d{1,2})$#', $value, $m) === 1) {
            $startTime = CarbonImmutable::create($m['year'], $m['month'], $m['day'], 0, 0, 0, 'UTC');
            $endTime = $startTime->addDays(1);
        } else {
            $startTime = parse_time_to_carbon($value)?->toImmutable()->utc();
            $endTime = $startTime?->addSeconds(1);
        }

        if (isset($startTime) && isset($endTime)) {
            return match ($operator) {
                '=' => [
                    'gte' => $startTime->getTimestampMs(),
                    'lt' => $endTime->getTimestampMs(),
                ],
                '<' => [
                    'lt' => $startTime->getTimestampMs(),
                ],
                '<=' => [
                    'lt' => $endTime->getTimestampMs(),
                ],
                '>' => [
                    'gte' => $endTime->getTimestampMs(),
                ],
                '>=' => [
                    'gte' => $startTime->getTimestampMs(),
                ],
            };
        }

        return null;
    }

    private static function makeFloatRangeOption($operator, $value, $tolerance)
    {
        // Some locales have `,` as decimal separator.
        // Note that thousand separator is not (yet?) supported.
        $value = str_replace(',', '.', $value);

        if (!is_numeric($value)) {
            return;
        }

        $value = get_float($value);

        switch ($operator) {
            case '=':
                return [
                    'gte' => $value - $tolerance,
                    'lte' => $value + $tolerance,
                ];
            case '<':
                return [
                    'lte' => $value - $tolerance,
                ];
            case '<=':
                return [
                    'lte' => $value + $tolerance,
                ];
            case '>':
                return [
                    'gte' => $value + $tolerance,
                ];
            case '>=':
                return [
                    'gte' => $value - $tolerance,
                ];
        }
    }

    private static function makeIntOption($operator, $value)
    {
        if (is_numeric($value) && $operator === '=') {
            return get_int($value);
        }
    }

    private static function makeIntRangeOption($operator, $value)
    {
        if (!is_numeric($value)) {
            return;
        }

        $value = get_int($value);

        switch ($operator) {
            case '=':
                return [
                    'gte' => $value,
                    'lte' => $value,
                ];
            case '<':
                return [
                    'lt' => $value,
                ];
            case '<=':
                return [
                    'lte' => $value,
                ];
            case '>':
                return [
                    'gt' => $value,
                ];
            case '>=':
                return [
                    'gte' => $value,
                ];
        }
    }

    private static function makeTextOption(string $operator, string $value): ?string
    {
        return $operator === '='
            ? presence(strtr(preg_replace('/^"(.*)"$/', '$1', $value), ['\\"' => '"']))
            : null;
    }

    private static function statePrefixSearch($value): ?int
    {
        if (!present($value)) {
            return null;
        }

        if (isset(Beatmapset::STATES[$value])) {
            return Beatmapset::STATES[$value];
        }

        foreach (Beatmapset::STATES as $string => $int) {
            if (starts_with($string, $value)) {
                return $int;
            }
        }

        return null;
    }
}
