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

var_dump(max($caloriesByElf));
