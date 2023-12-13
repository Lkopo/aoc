<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$seeds = [];
$maps = [];
$source = null;
$destination = null;
foreach ($lines as $line) {
    if (preg_match('/seeds: ([\d ]+)/', $line, $matches)) {
        $seeds = explode(' ', $matches[1]);
        continue;
    }
    if (preg_match('/(\w+)-to-(\w+) map/', $line, $matches)) {
        $source = $matches[1];
        $destination = $matches[2];
        continue;
    }
    if (preg_match_all('/\d+/', $line, $matches) && $source && $destination) {
        $maps[$source][$destination][] = [
            'source' => (int) $matches[0][1],
            'destination' => (int) $matches[0][0],
            'iterations' => (int) end($matches[0])
        ];
        continue;
    }
    $source = null;
    $destination = null;
}

$locations = [];
foreach ($seeds as $seed) {
    $source = 'seed';
    $number = $seed;
    while (isset($maps[$source])) {
        $destination = current(array_keys($maps[$source]));
        foreach ($maps[$source][$destination] as $startConfig) {
            $sourceStart = $startConfig['source'];
            $sourceEnd = $sourceStart + $startConfig['iterations'] - 1;
            if ($number >= $sourceStart && $number <= $sourceEnd) {
                $diff = $number - $sourceStart;
                $number = $startConfig['destination'] + $diff;
                break;
            }
        }
        $source = $destination;
    }
    $locations[] = $number;
}

var_dump(min($locations));
