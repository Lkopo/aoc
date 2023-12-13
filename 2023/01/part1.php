<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    $numbers = preg_replace('/[^0-9]/', '', $line);
    if (empty($numbers)) {
        return 0;
    }
    $total += (int) sprintf('%s%s', $numbers[0], $numbers[-1]);
}

var_dump($total);
