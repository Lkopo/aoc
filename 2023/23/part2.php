<?php

$lines = file('input_test.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$size = count($map);
$start = [0, array_search('.', $map[1])];
$end = [$size - 1, array_search('.', $map[$size - 1])];
$queue = new SplMaxHeap();
$queue->insert([0, $start, []]);
$maxDist = 0;
while (!$queue->isEmpty()) {
    [$dist, [$x, $y], $visited] = $queue->extract();
    if (isset($visited[$x][$y])) {
        continue;
    }
    $visited[$x][$y] = 1;
    if ($x === $end[0] && $y === $end[1]) {
        $maxDist = max($maxDist, $dist);
        var_dump($maxDist);
        continue;
    }
    foreach ($directions as $direction) {
        [$newX, $newY] = [$x + $direction[0], $y + $direction[1]];
        if (isset($map[$newX][$newY]) && $map[$newX][$newY] !== '#') {
            $queue->insert([$dist + 1, [$newX, $newY], $visited]);
        }
    }
}

var_dump($maxDist);
