<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$treeMap = [];
foreach ($lines as $line) {
    $treeMap[] = array_map(fn(string $digit) => (int) $digit, str_split($line));
}

$visibleTrees = [];
$size = count($treeMap);
for ($i = 1; $i < $size - 1; ++$i) {
    $highestTreeFirstTree = $treeMap[$i][0];
    $highestTreeLastTree = end($treeMap[$i]);
    for ($j = 1; $j < $size - 1; ++$j) {
        if ($treeMap[$i][$j] > $highestTreeFirstTree) {
            $visibleTrees[sprintf('%s-%s', $i, $j)] = 1;
            $highestTreeFirstTree = $treeMap[$i][$j];
        }
        if ($treeMap[$i][$size - $j - 1] > $highestTreeLastTree) {
            $visibleTrees[sprintf('%s-%s', $i, $size - $j - 1)] = 1;
            $highestTreeLastTree = $treeMap[$i][$size - $j - 1];
        }
    }
}
for ($j = 1; $j < $size - 1; ++$j) {
    $highestTreeFirstTree = $treeMap[0][$j];
    $highestTreeLastTree = end($treeMap)[$j];
    for ($i = 1; $i < $size - 1; ++$i) {
        if ($treeMap[$i][$j] > $highestTreeFirstTree) {
            $visibleTrees[sprintf('%s-%s', $i, $j)] = 1;
            $highestTreeFirstTree = $treeMap[$i][$j];
        }
        if ($treeMap[$size - $i - 1][$j] > $highestTreeLastTree) {
            $visibleTrees[sprintf('%s-%s', $size - $i - 1, $j)] = 1;
            $highestTreeLastTree = $treeMap[$size - $i - 1][$j];
        }
    }
}

var_dump(count($visibleTrees) + (count($treeMap) - 1) * 4);
