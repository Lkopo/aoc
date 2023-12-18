<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = [];
$minX = $maxX = $minY = $maxY = 0;
$directions = [
    'L' => [0, -1],
    'U' => [-1, 0],
    'R' => [0, 1],
    'D' => [1, 0],
];
$pos = [0, 0];
foreach ($lines as $line) {
    preg_match('/(\w) (\d+)/', $line, $matches);
    for ($i = 0; $i < $matches[2]; ++$i) {
        $map[$pos[0]][$pos[1]] = '#';
        $minX = min($minX, $pos[0]);
        $maxX = max($maxX, $pos[0]);
        $minY = min($minY, $pos[1]);
        $maxY = max($maxY, $pos[1]);
        $pos[0] += $directions[$matches[1]][0];
        $pos[1] += $directions[$matches[1]][1];
    }
}

$total = 0;
for ($x = $minX; $x <= $maxX; ++$x) {
    $inside = false;
    for ($y = $minY; $y <= $maxY; ++$y) {
        if (isset($map[$x][$y])) {
            if (isset($map[$x - 1][$y])) {
                $inside = !$inside;
            }
            ++$total;
        } elseif ($inside) {
            ++$total;
        }
    }
}

var_dump($total);
