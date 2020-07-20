<?php


namespace App\Service;


class Slugify
{
    const SPACE_PATTERN = [
        '/[^\pL\d]+/u',
    ];
    const SPECIAL_PATTERN = [
        '/[^\w-]/',
    ];
    const MULTIPLE_HYPHENS = [
        '/-+/',
    ];

    /**
     * @param string $input
     * @return string
     */
    public function generate(string $input): string
    {
        // replaces whitespaces by hyphen
        $string = preg_replace(self::SPACE_PATTERN, '-', $input);
        // transliteration from UTF-8 to ASCII (convert accented characters)
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        // remove special characters
        $string = preg_replace(self::SPECIAL_PATTERN, '', $string);
        // trim but the hyphens
        $string = trim($string, '-');
        // replace multiple hyphens by one hyphen
        $string = preg_replace(self::MULTIPLE_HYPHENS, '-', $string);
        // To lower case
        $string = strtolower($string);

        return $string;
    }
}
