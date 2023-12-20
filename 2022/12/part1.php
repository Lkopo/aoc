<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$map = array_map(fn(string $line) => str_split($line), $lines);
$flat = array_merge(...$map);
$startKey = array_search('S', $flat);
$endKey = array_search('E', $flat);
$start = [(int) ($startKey / count($map[0])), $startKey % count($map[0])];
$end = [(int) ($endKey / count($map[0])), $endKey % count($map[0])];
$map[$start[0]][$start[1]] = 'a';
$map[$end[0]][$end[1]] = 'z';
$queue = new SplMinHeap();
$queue->insert([0, $start]);
$visited = [];
$minDist = INF;
while (!$queue->isEmpty()) {
    [$dist, [$x, $y]] = $queue->extract();
    $key = "$x:$y";
    if (isset($visited[$key])) {
        continue;
    }
    $visited[$key] = 1;
    if ($x === $end[0] && $y === $end[1]) {
        $minDist = min($minDist, $dist);
        continue;
    }
    foreach ($directions as $direction) {
        [$newX, $newY] = [$x + $direction[0], $y + $direction[1]];
        if (isset($map[$newX][$newY]) && ord($map[$newX][$newY]) - ord($map[$x][$y]) <= 1) {
            $queue->insert([$dist + 1, [$newX, $newY]]);
        }
    }
}

var_dump($minDist);
