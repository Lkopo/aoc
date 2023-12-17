<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => array_map(fn(string $char) => (int) $char, str_split($line)), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$perpendicularDirections = [
    1 => [0, 2],
    0 => [1, 3],
];
$queue = new SplMinHeap();
$queue->insert([0, [0, 0, 2, 0]]);
$queue->insert([0, [0, 0, 3, 0]]);
$dists = [];
$dX = $dY = count($map) - 1;
$minDist = INF;
while (!$queue->isEmpty()) {
    [$dist, [$x, $y, $direction, $steps]] = $queue->extract();
    $key = "$x:$y:$direction:$steps";
    if (isset($dists[$key])) {
        continue;
    }
    $dists[$key] = $dist;
    if ($x === $dX && $y === $dY && $steps >= 4) {
        $minDist = min($minDist, $dist);
        continue;
    }
    if ($steps < 10) {
        [$newX, $newY] = [$x + $directions[$direction][0], $y + $directions[$direction][1]];
        if (isset($map[$newX][$newY])) {
            $queue->insert([$map[$newX][$newY] + $dist, [$newX, $newY, $direction, $steps + 1]]);
        }
    }
    if ($steps >= 4) {
        foreach ($perpendicularDirections[abs($directions[$direction][0])] as $direction) {
            [$newX, $newY] = [$x + $directions[$direction][0], $y + $directions[$direction][1]];
            if (isset($map[$newX][$newY])) {
                $queue->insert([$map[$newX][$newY] + $dist, [$newX, $newY, $direction, 1]]);
            }
        }
    }
}

var_dump($minDist);
