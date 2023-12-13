<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
preg_match_all('/\d+/', $lines[0], $matches);
$times = $matches[0];
preg_match_all('/\d+/', $lines[1], $matches);
$distances = $matches[0];
$time = intval(implode('', $times));
$distance = intval(implode('', $distances)) + 1;
$middle = $time / 2;
$middleDistance = ($time - $middle) * $middle;
$sqrt = sqrt($middleDistance - $distance);
$min = ceil($middle - $sqrt);
$max = floor($middle + $sqrt);
$total = $max - $min + 1;

var_dump($total);
