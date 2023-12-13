<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$caloriesByElf = [];
$elfCount = 0;
foreach ($lines as $line) {
    if (!isset($caloriesByElf[$elfCount])) {
        $caloriesByElf[$elfCount] = 0;
    }
    if (empty($line)) {
        ++$elfCount;
        continue;
    }
    $caloriesByElf[$elfCount] += (int) $line;
}

if (count($caloriesByElf) < 3) {
    exit('Low number of elfs, at least 3 are required' . PHP_EOL);
}

rsort($caloriesByElf);
var_dump($caloriesByElf[0] + $caloriesByElf[1] + $caloriesByElf[2]);
