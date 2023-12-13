<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$allowedConfiguration = [
    'red' => 12,
    'green' => 13,
    'blue' => 14
];
$total = 0;
foreach ($lines as $line) {
    if (!preg_match('/Game (\d+): (.*)/', $line, $matches)) {
        continue;
    }
    $gameId = $matches[1];
    foreach (explode(';', $matches[2]) as $configuration) {
        preg_match_all('/(\d+) (\w+),?/', $configuration, $matches, PREG_SET_ORDER);
        foreach ($matches as $sets) {
            if ($sets[1] > $allowedConfiguration[$sets[2]]) {
                $gameId = 0;
            }
        }
    }

    $total += $gameId;
}

var_dump($total);
