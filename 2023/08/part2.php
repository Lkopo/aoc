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
$currentNodes = array_filter(array_keys($map), fn(string $key) => str_ends_with($key, 'A'));
$nodeSteps = [];
foreach ($currentNodes as $currentNode) {
    $steps = 0;
    while (!str_ends_with($currentNode, 'Z')) {
        $command = $commands[0];
        array_shift($commands);
        $currentNode = $map[$currentNode][$command];
        ++$steps;
        $commands[] = $command;
    }
    $nodeSteps[] = $steps;
}

var_dump(array_reduce($nodeSteps, fn(mixed $total, mixed $steps) => gmp_lcm($total, $steps), 1));
