<?php

$line = file('input.txt', FILE_IGNORE_NEW_LINES)[0];
$boxes = [];
foreach (explode(',', $line) as $step) {
    preg_match('/([a-z]+)([-=])(\d)?/', $step, $matches);
    $box = array_reduce(str_split($matches[1]), fn(int $hash, string $char) => ($hash + ord($char)) * 17 % 256, 0);
    if ($matches[2] === '=') {
        $boxes[$box][$matches[1]] = $matches[3];
    } else {
        unset($boxes[$box][$matches[1]]);
    }
}

$total = 0;
foreach ($boxes as $boxIdx => $box) {
    $i = 1;
    foreach ($box as $len) {
        $total += ($boxIdx + 1) * $i++ * $len;
    }
}

var_dump($total);
