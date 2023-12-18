<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$monkeyConfiguration = [];
$monkey = null;
foreach ($lines as $line) {
    if (preg_match('/Monkey (\d+)/', $line, $matches)) {
        $monkey = (int) $matches[1];
        $monkeyConfiguration[$monkey]['processedItems'] = 0;
    } elseif (str_contains($line,'Starting') && preg_match_all('/\d+/', $line, $matches)) {
        $monkeyConfiguration[$monkey]['items'] = array_map(fn(string $item) => (int) $item, $matches[0]);
    } elseif (preg_match('/Operation: new = (.*)/', $line, $matches)) {
        $monkeyConfiguration[$monkey]['operation'] = $matches[1];
    } elseif (preg_match('/divisible by (\d+)/', $line, $matches)) {
        $monkeyConfiguration[$monkey]['test'] = $matches[1];
    } elseif (preg_match('/If (true|false): throw to monkey (\d+)/', $line, $matches)) {
        $monkeyConfiguration[$monkey]['move-' . (int) filter_var($matches[1], FILTER_VALIDATE_BOOLEAN)] = $matches[2];
    }
}

for ($i = 0; $i < 20; ++$i) {
    for ($m = 0; $m < count($monkeyConfiguration); ++$m) {
        foreach ($monkeyConfiguration[$m]['items'] as $idx => $item) {
            $worryLevel = (int) (eval('return ' . str_replace('old', $item, $monkeyConfiguration[$m]['operation']) . ';') / 3);
            $monkeyConfiguration[$monkeyConfiguration[$m][
                'move-' . (int) ($worryLevel % $monkeyConfiguration[$m]['test'] === 0)]
            ]['items'][] = $worryLevel;
            unset($monkeyConfiguration[$m]['items'][$idx]);
            ++$monkeyConfiguration[$m]['processedItems'];
        }
    }
}

usort($monkeyConfiguration, fn(array $c1, array $c2) => $c2['processedItems'] - $c1['processedItems']);
var_dump($monkeyConfiguration[0]['processedItems'] * $monkeyConfiguration[1]['processedItems']);
