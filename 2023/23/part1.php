<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$forcedDirections = [
    '>' => [0, 1],
    '<' => [0, -1],
    'v' => [1, 0],
    '^' => [-1, 0]
];
$size = count($map);
$start = [0, array_search('.', $map[0])];
$end = [$size - 1, array_search('.', $map[$size - 1])];
$queue = new SplPriorityQueue();
$queue->insert([0, $start, []], 0);
$maxDist = 0;
while (!$queue->isEmpty()) {
    [$dist, [$x, $y], $visited] = $queue->extract();
    $visited[$x][$y] = 1;
    if ($x === $end[0] && $y === $end[1]) {
        $maxDist = max($maxDist, $dist);
        continue;
    }
    if (isset($forcedDirections[$map[$x][$y]])) {
        $newCoords = [$x + $forcedDirections[$map[$x][$y]][0], $y + $forcedDirections[$map[$x][$y]][1]];
        if (!isset($visited[$newCoords[0]][$newCoords[1]])) {
            $queue->insert([$dist + 1, $newCoords, $visited], $dist + 1);
        }
    } else {
        foreach ($directions as $direction) {
            [$newX, $newY] = [$x + $direction[0], $y + $direction[1]];
            if (isset($map[$newX][$newY]) && $map[$newX][$newY] !== '#' && !isset($visited[$newX][$newY])) {
                $queue->insert([$dist + 1, [$newX, $newY], $visited], $dist + 1);
            }
        }
    }
}

var_dump($maxDist);
