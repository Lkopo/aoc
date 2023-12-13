<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
preg_match_all('/\d+/', $lines[0], $matches);
$times = $matches[0];
preg_match_all('/\d+/', $lines[1], $matches);
$distances = $matches[0];
$total = 1;
for ($i = 0; $i < count($times); ++$i) {
    $middle = $times[$i] / 2;
    $middleDistance = ($times[$i] - $middle) * $middle;
    $sqrt = sqrt($middleDistance - $distances[$i]);
    $min = ceil($middle - $sqrt);
    $max = floor($middle + $sqrt);
    $total *= ($max - $min + 1);
}

var_dump($total);
