<?php

namespace Hotmeteor\Titles;

use Hotmeteor\Titles\Enums\Style;
use Exception;

class TitleFactory
{
    public function __construct(public string $lang)
    {
    }

    public static function title(string $value, Style $style): string
    {
        return match($style) {
            Style::APA => (new TitleFactory)->apa($value);
            default => throw new Exception("The style \"{$style->name}\" is currently not implemented");
        };
    }

    /**
     * Convert the given string to APA-style title case.
     *
     * See: https://apastyle.apa.org/style-grammar-guidelines/capitalization/title-case
     *
     * @param  string  $value
     * @return string
     */
    public function apa(string $value): string
    {
        $conjunctions = $this->load('conjunctions');
        $endPunctuation = $this->load('end_punctuation');

        $words = preg_split('/\s+/', $value, -1, PREG_SPLIT_NO_EMPTY);

        $words[0] = ucfirst(mb_strtolower($words[0]));

        for ($i = 0; $i < count($words); $i++) {
            $lowercaseWord = mb_strtolower($words[$i]);

            if (str_contains($lowercaseWord, '-')) {
                $hyphenatedWords = explode('-', $lowercaseWord);

                $hyphenatedWords = array_map(function ($part) use ($conjunctions) {
                    return (in_array($part, $conjunctions) && mb_strlen($part) <= 3) ? $part : ucfirst($part);
                }, $hyphenatedWords);

                $words[$i] = implode('-', $hyphenatedWords);
            } else {
                if (in_array($lowercaseWord, $conjunctions) &&
                    mb_strlen($lowercaseWord) <= 3 &&
                    ! ($i === 0 || in_array(mb_substr($words[$i - 1], -1), $endPunctuation))) {
                    $words[$i] = $lowercaseWord;
                } else {
                    $words[$i] = ucfirst($lowercaseWord);
                }
            }
        }

        return implode(' ', $words);
    }

    /**
     * @param string $key
     * @return array
     */
    protected function load(string $key): array
    {
        $values = require_once __DIR__ . "/../lang/{$this->lang}.php";

        return data_get($values, $key);
    }
}