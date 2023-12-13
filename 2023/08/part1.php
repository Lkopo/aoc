<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$commands = str_split($lines[0]);
$map = [];
foreach ($lines as $line) {
    if (preg_match('/(\w+) = \((\w+), (\w+)\)/', $line, $matches)) {
        $map[$matches[1]]['L'] = $matches[2];
        $map[$matches[1]]['R'] = $matches[3];
    }
}
$steps = 0;
$currentNode = 'AAA';
while ($currentNode !== 'ZZZ') {
    $command = $commands[0];
    array_shift($commands);
    $currentNode = $map[$currentNode][$command];
    ++$steps;
    $commands[] = $command;
}

var_dump($steps);
