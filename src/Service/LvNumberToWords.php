<?php

namespace App\Service;

class LvNumberToWords
{
    private static array $base = [
        null,
        'viens',
        'divi',
        'trīs',
        'četri',
        'pieci',
        'seši',
        'septiņi',
        'astoņi',
        'deviņi',
    ];

    private static array $tens = [
        null,
        null,
        'divdesmit',
        'trisdesmit',
        'četrdesmit',
        'piecdesmit',
        'sešdesmit',
        'septiņdesmit',
        'astoņdesmit',
        'deviņdesmit',
    ];

    private static array $teens = [
        'desmit',
        'vienpadsmit',
        'divpadsmit',
        'trispadsmit',
        'četrpadsmit',
        'piecpadsmit',
        'sešpadsmit',
        'septiņpadsmit',
        'astoņpadsmit',
        'deviņpadsmit',
    ];

    private static array $hundred = ['simts', 'simti'];

    private static array $classes = [
        null,
        ['tukstotis', 'tukstosi'],
        ['miljons', 'miljoni'],
        ['miljards', 'miljardi'],
        ['trilijons', 'triljioni'],
    ];

    public static function toWords(int $number): string
    {
        $parts  = self::split($number, 1000);
        if (count($parts) > count(self::$classes)) {
            throw new \OutOfBoundsException('This number is too large to convert!');
        }

        $i      = 0;
        $result = [];

        foreach ($parts as $part) {


            $result = [...$result, ...self::convertWithBase($part, self::$classes[$i++])];
        }

        $combined = implode(' ', array_filter(array_reverse($result)));

        if (!strlen($combined)) {
            return 'nulle';
        }

        return $combined;
    }

    private static function convertWithBase(int $number, ?array $class): array
    {
        $result = [];

        $parts    = self::split($number, 10);
        $result[] = self::$base[$parts[0]] ?? '';

        if (!empty($parts[1])) {
            if ($parts[1] == 1) {
                // handle teens
                $result = [self::$teens[$parts[0]]];
            } else {
                $result[] = self::$tens[$parts[1]];
            }
        }

        if (!empty($parts[2])) {
            if ($parts[2] == 1) {
                $result[] = self::$hundred[0];
            } else {
                $result[] = self::$hundred[1];
            }

            $result[] = self::$base[$parts[2]];
        }

        if ($class && $number) {
            $result = [$number == 1 ? $class[0] : $class[1], ...$result,];
        }

        return $result;
    }

    private static function split(int $number, int $scale): array
    {
        $result = [];

        do {
            $result[] = $number % $scale;
            $number   = intdiv($number, $scale);
        } while ($number > 0);

        return $result;
    }
}
