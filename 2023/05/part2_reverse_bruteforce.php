<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$seeds = [];
$maps = [];
$sourcePerDestination = [];
$source = null;
$destination = null;
foreach ($lines as $line) {
    if (preg_match('/seeds: ([\d ]+)/', $line, $matches)) {
        preg_match_all('/(\d+ \d+)/', $matches[1], $matches);
        foreach ($matches[0] as $pair) {
            list($number, $iterations) = explode(' ', $pair);
            $seeds[] = [
                'start' => (int) $number,
                'iterations' => (int) $iterations
            ];
        }
        continue;
    }
    if (preg_match('/(\w+)-to-(\w+) map/', $line, $matches)) {
        $source = $matches[1];
        $destination = $matches[2];
        continue;
    }
    if (preg_match_all('/\d+/', $line, $matches) && $source && $destination) {
        $iterations = (int) end($matches[0]);
        $sourceStart = (int) $matches[0][1];
        $destinationStart = (int) $matches[0][0];
        $maps[$source][$destination][] = [
            'source' => $sourceStart,
            'destination' => $destinationStart,
            'iterations' => $iterations
        ];
        $sourcePerDestination[$destination] = $source;
        continue;
    }
    $source = null;
    $destination = null;
}

$location = 0;
$locationFound = false;
while (!$locationFound) {
    $number = ++$location;
    $destination = 'location';
    while (isset($maps[$source]) && isset($sourcePerDestination[$destination])) {
        $source = $sourcePerDestination[$destination];
        foreach ($maps[$source][$destination] as $startConfig) {
            $destinationStart = $startConfig['destination'];
            $destinationEnd = $destinationStart + $startConfig['iterations'] - 1;

            if ($number >= $destinationStart && $number <= $destinationEnd) {
                $diff = $number - $destinationStart;
                $number = $startConfig['source'] + $diff;
                break;
            }
        }

        $destination = $source;
    }

    foreach ($seeds as $seedConfiguration) {
        $seedStart = (int) $seedConfiguration['start'];
        $seedEnd = $seedStart + (int) $seedConfiguration['iterations'] - 1;

        if ($number >= $seedStart && $number <= $seedEnd) {
            $locationFound = true;
            break;
        }
    }
}

var_dump($location);
