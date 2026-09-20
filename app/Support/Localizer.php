<?php

namespace App\Support;

class Localizer
{
    public const LANGUAGES = ['hy', 'en', 'ru'];

    public static function language(?string $lang): string
    {
        $lang = strtolower((string) $lang);

        return in_array($lang, self::LANGUAGES, true) ? $lang : 'hy';
    }

    /**
     * Collapse {hy,en,ru} objects (at any depth) to a single language,
     * falling back to the other languages when a translation is empty.
     */
    public static function localize(mixed $value, string $lang): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        $languageKeys = array_intersect(array_keys($value), self::LANGUAGES);
        if ($languageKeys !== []) {
            foreach ([$lang, 'hy', 'en', 'ru'] as $candidate) {
                $candidateValue = $value[$candidate] ?? null;
                if ($candidateValue !== null && $candidateValue !== '') {
                    return $candidateValue;
                }
            }

            return null;
        }

        return array_map(fn ($item) => self::localize($item, $lang), $value);
    }
}
