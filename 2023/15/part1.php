<?php

$line = file('input.txt', FILE_IGNORE_NEW_LINES)[0];
$total = 0;
foreach (explode(',', $line) as $step) {
    $total += array_reduce(str_split($step), fn(int $hash, string $char) => ($hash + ord($char)) * 17 % 256, 0);
}

var_dump($total);
