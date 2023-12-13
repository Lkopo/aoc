<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$seeds = [];
$maps = [];
$destinationPerSource = [];
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
        $destinationPerSource[$source] = $destination;
        continue;
    }
    $source = null;
    $destination = null;
}

$allItemsCount = 0;
foreach ($seeds as $seedConfiguration) {
    $allItemsCount += $seedConfiguration['iterations'];
}

echo 'Total seeds: ' . number_format($allItemsCount, 0, ',', ' ') . PHP_EOL;

$processed = 0;
$lowestLocation = PHP_INT_MAX;
foreach ($seeds as $seedConfiguration) {
    $seedStart = $seedConfiguration['start'];
    $seedIterations = $seedConfiguration['iterations'];
    for ($i = 0; $i < $seedIterations; ++$i) {
        $source = 'seed';
        $number = $seedStart + $i;
        while (isset($maps[$source])) {
            $destination = $destinationPerSource[$source];
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
        if ($number < $lowestLocation) {
            $lowestLocation = $number;
        }
        ++$processed;
        if ($processed % 10000000 === 0 || $processed === $allItemsCount) {
            echo sprintf(
                "Processed %s / %s (%s%%)\n",
                number_format($processed, 0, ',', ' '),
                number_format($allItemsCount, 0, ',', ' '),
                intval($processed / $allItemsCount * 100),
            );
        }
    }
}

var_dump($lowestLocation);
