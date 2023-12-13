<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$crateMap = [];
$commands = [];
foreach ($lines as $idx => $line) {
    if (preg_match_all('/\[(\w)\]/', $line, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[1] as $crate) {
            $crateMap[intval(($crate[1] / 4) + 1)][] = $crate[0];
        }
    }
    if (preg_match('/move (\d+) from (\d)+ to (\d+)/', $line, $matches)) {
        $commands[] = [
            'count' => $matches[1],
            'sourceColumn' => $matches[2],
            'targetColumn' => $matches[3],
        ];
    }
}

ksort($crateMap);

$crateMap = array_map('array_reverse', $crateMap);
foreach ($commands as $command) {
    $buffer = [];
    for ($i = 0; $i < $command['count']; ++$i) {
        $buffer[] = array_pop($crateMap[$command['sourceColumn']]);
    }
    array_push($crateMap[$command['targetColumn']], ...array_reverse($buffer));
}

foreach ($crateMap as $crateStack) {
    echo end($crateStack);
}

echo PHP_EOL;
