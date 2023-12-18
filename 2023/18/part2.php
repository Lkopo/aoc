<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$directions = [
    '2' => [0, -1],
    '3' => [-1, 0],
    '0' => [0, 1],
    '1' => [1, 0],
];
$pos = [0, 0];
$area = 0;
$boundary = 0;
foreach ($lines as $line) {
    preg_match('/#(\w+)(\d)/', $line, $matches);
    $newPos = $pos;
    $length = hexdec($matches[1]);
    $newPos[0] += $directions[$matches[2]][0] * $length;
    $newPos[1] += $directions[$matches[2]][1] * $length;
    $boundary += $length;
    $area += ($pos[1] + $newPos[1]) * ($pos[0] - $newPos[0]);
    $pos = $newPos;
}

var_dump(abs($area / 2) + $boundary / 2 + 1);
