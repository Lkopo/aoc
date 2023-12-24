<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$size = count($map);
$start = [0, array_search('.', $map[1])];
$end = [$size - 1, array_search('.', $map[$size - 1])];
$nodes = [];
$queue = [[0, $start, $start, $directions[3], $directions[3]]];
while (!empty($queue)) {
    [$dist, [$sx, $sy], [$x, $y], [$dx, $dy]] = array_pop($queue);
    [$startKey, $currentKey] = [$sx * 1000 + $sy, $x * 1000 + $y];
    if (($x === $end[0] && $y === $end[1]) || isset($nodes[$currentKey])) {
        $nodes[$startKey][$currentKey] = $dist;
        $nodes[$currentKey][$startKey] = $dist;
        continue;
    }
    $possibleDirections = [];
    foreach ($directions as $direction) {
        if ($direction === [-$dx, -$dy]) {
            continue;
        }
        [$newX, $newY] = [$x + $direction[0], $y + $direction[1]];
        if (isset($map[$newX][$newY]) && $map[$newX][$newY] !== '#') {
            $possibleDirections[] = [[$newX, $newY], $direction];
        }
    }
    if (count($possibleDirections) > 1) {
        $nodes[$startKey][$currentKey] = $dist;
        $nodes[$currentKey][$startKey] = $dist;
        foreach ($possibleDirections as $possibleDirection) {
            $queue[] = ([1, [$x, $y], $possibleDirection[0], $possibleDirection[1]]);
        }
    } elseif (!empty($possibleDirections)) {
        $queue[] = ([$dist + 1, [$sx, $sy], $possibleDirections[0][0], $possibleDirections[0][1]]);
    }
}

$queue = [[0, $start, []]];
$maxDist = 0;
while (!empty($queue)) {
    [$dist, [$x, $y], $visited] = array_pop($queue);
    $visited[$x][$y] = 1;
    if ($x === $end[0] && $y === $end[1]) {
        $maxDist = max($maxDist, $dist);
        continue;
    }
    foreach ($nodes[$x * 1000 + $y] as $childCoords => $childDist) {
        $newX = (int) ($childCoords / 1000);
        $newY = $childCoords - $newX * 1000;
        if (!isset($visited[$newX][$newY])) {
            $queue[] = [$dist + $childDist, [$newX, $newY], $visited];
        }
    }
}

var_dump($maxDist);
