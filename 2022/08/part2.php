<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);

function get_score(int $row, int $col, array $treeMap): int
{
    $scores = [0, 0, 0, 0];
    $treeValue = $treeMap[$row][$col];
    $size = count($treeMap);
    foreach ($treeMap[$row] as $j => $value) {
        if ($j === $col) continue;
        if ($j < $col) {
            $scores[0] = $value >= $treeValue ? 1 : $scores[0] + 1;
        } else {
            ++$scores[1];
            if ($value >= $treeValue) break;
        }
    }
    for ($i = 0; $i < $size; ++$i) {
        if ($i === $row) continue;
        $value = $treeMap[$i][$col];
        if ($i < $row) {
            $scores[2] = $value >= $treeValue ? 1 : $scores[2] + 1;
        } else {
            ++$scores[3];
            if ($value >= $treeValue) break;
        }
    }
    return array_reduce($scores, fn(int $total, int $score) => $total * ($score ?: 1), 1);
}

$treeMap = [];
foreach ($lines as $line) {
    $treeMap[] = array_map(fn(string $digit) => (int) $digit, str_split($line));
}

$visibleTrees = [];
$size = count($treeMap);
$highestScore = 1;
for ($i = 1; $i < $size - 1; ++$i) {
    for ($j = 1; $j < $size - 1; ++$j) {
        $score = get_score($i, $j, $treeMap);
        if ($score > $highestScore) {
            $highestScore = $score;
        }
    }
}

var_dump($highestScore);
