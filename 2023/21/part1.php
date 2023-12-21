<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$startKey = array_search('S', array_merge(...$map));
$start = [(int) ($startKey / count($map[0])), $startKey % count($map[0])];
$neighbors[$start[0]][$start[1]] = 1;
$steps = 0;
do {
    $queue = new SplQueue();
    foreach (array_keys($neighbors) as $x) {
        foreach (array_keys($neighbors[$x]) as $y) {
            $queue->enqueue([$x, $y]);
        }
    }
    $neighbors = [];
    while (!$queue->isEmpty()) {
        [$x, $y] = $queue->dequeue();
        foreach ($directions as $direction) {
            [$newX, $newY] = [$x + $direction[0], $y + $direction[1]];
            if (isset($map[$newX][$newY]) && $map[$newX][$newY] !== '#') {
                $neighbors[$newX][$newY] = 1;
            }
        }
    }
    ++$steps;
} while ($steps < 64);

var_dump(count(array_merge(...$neighbors)));