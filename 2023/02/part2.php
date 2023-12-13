<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    if (!preg_match('/Game \d+: (.*)/', $line, $matches)) {
        continue;
    }
    $requiredConfiguration = [
        'red' => 0,
        'green' => 0,
        'blue' => 0
    ];
    foreach (explode(';', $matches[1]) as $configuration) {
        preg_match_all('/(\d+) (\w+),?/', $configuration, $matches, PREG_SET_ORDER);
        foreach ($matches as $sets) {
            if ($sets[1] > $requiredConfiguration[$sets[2]]) {
                $requiredConfiguration[$sets[2]] = $sets[1];
            }
        }
    }

    $total += $requiredConfiguration['red'] * $requiredConfiguration['green'] * $requiredConfiguration['blue'];
}

var_dump($total);
