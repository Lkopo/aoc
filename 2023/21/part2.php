<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$startKey = array_search('S', array_merge(...$map));
$start = [(int) ($startKey / count($map[0])), $startKey % count($map[0])];
$neighbors[$start[0]][$start[1]] = 1;
$steps = 0;
$size = count($map);
$gridBoundNeighborLen = [];
$lengthsCount = 0;
$maxSteps = 26501365;
do {
    ++$steps;
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
            $augmentedX = $newX >= 0 ? $newX % $size : ($size + $newX % $size) % $size;
            $augmentedY = $newY >= 0 ? $newY % $size : ($size + $newY % $size) % $size;
            if ($map[$augmentedX][$augmentedY] !== '#') {
                $neighbors[$newX][$newY] = 1;
            }
        }
    }
    if ($steps % $size === 65) {
        $gridBoundNeighborLen[$lengthsCount++] =
            [
                'x' => $steps,
                'y' => count(array_merge(...$neighbors))
            ];
    }
} while ($steps < $maxSteps && $lengthsCount < 3);

// https://www.pw.live/exams/school/quadratic-interpolation-formula/
$x = $maxSteps;
$x0 = $gridBoundNeighborLen[0]['x'];
$x1 = $gridBoundNeighborLen[1]['x'];
$x2 = $gridBoundNeighborLen[2]['x'];
$y0 = $gridBoundNeighborLen[0]['y'];
$y1 = $gridBoundNeighborLen[1]['y'];
$y2 = $gridBoundNeighborLen[2]['y'];
$l0 = ($x - $x1) * ($x - $x2) / (($x0 - $x1) * ($x0 - $x2));
$l1 = ($x - $x0) * ($x - $x2) / (($x1 - $x0) * ($x1 - $x2));
$l2 = ($x - $x0) * ($x - $x1) / (($x2 - $x0) * ($x2 - $x1));

var_dump($y0 * $l0 + $y1 * $l1 + $y2 * $l2);
