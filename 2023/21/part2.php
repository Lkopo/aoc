<?php

// hardcoded values used as this solution works only with real input (contains horizontal & vertical line from start)
$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$directions = [[0, -1], [-1, 0], [0, 1], [1, 0]];
$start = [65, 65];
$neighbors[65][65] = 1;
$steps = $lengthsCount = 0;
$gridBoundNeighborLen = [];
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
            $augmentedX = $newX >= 0 ? $newX % 131 : (131 + $newX % 131) % 131;
            $augmentedY = $newY >= 0 ? $newY % 131 : (131 + $newY % 131) % 131;
            if ($map[$augmentedX][$augmentedY] !== '#') {
                $neighbors[$newX][$newY] = 1;
            }
        }
    }
    if ($steps % 131 === 65) {
        $gridBoundNeighborLen[$lengthsCount++] = count(array_merge(...$neighbors));
    }
} while ($steps < $maxSteps && $lengthsCount < 3);

// P(x) = ax^2 + bx + c, we know P(0), P(1) & P(2)
// c = P(0)
// b = P(1) - a - P(0)
// a = (P(2) - 2(P1) + P(0)) / 2
// x = if we got P(n) for every $steps % 131 == 65, this means we got neighbors last step before crossing first square,
//     second square & third square;
//     start pos is 65, and it takes 131 steps to the start position of another square;
//     we need to set X as number of all squares we cross, so ($maxSteps - 65) / 131 = 202 300 squares
$x = ($maxSteps - 65) / 131;
$a = ($gridBoundNeighborLen[2] - 2 * $gridBoundNeighborLen[1] + $gridBoundNeighborLen[0]) / 2;
$b = $gridBoundNeighborLen[1] - $a - $gridBoundNeighborLen[0];

var_dump($a * $x ** 2 + $b * $x + $gridBoundNeighborLen[0]);
