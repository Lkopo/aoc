<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$startKey = array_search('S', array_merge(...$map));
$start = [(int) ($startKey / count($map[0])), $startKey % count($map[0])];
$neighbors[$start[0]][$start[1]] = 1;
$steps = 0;
$size = count($map);
$neighborLengths = [];
$p1 = $p2 = $p3 = 0;
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
    if ($steps % 131 === 65) {
        $c = count(array_merge(...$neighbors));
        echo "S: ($steps, $c)\n";
        $d1 = $c - $p1;
        $p1 = $c;
        echo "D1: $d1\n";

        $d2 = $d1 - $p2;
        $p2 = $d1;
        echo "D2: $d2\n";

        $d3 = $d2 - $p3;
        $p3 = $d2;
        echo "D3: $d3\n";
    }
} while ($steps < 500);

// after figuring out that function is quadratic
$s = 589;
$ct = 315795;
$st = 124661 + 31134;

while ($s < 26501365) {
    $s += 131;
    $ct += $st;
    $st += 31134;
}

var_dump($ct);
