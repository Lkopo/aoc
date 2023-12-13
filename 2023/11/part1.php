<?php

function get_pairs(array $galaxies, int $size, int $startIdx = 0): array
{
    $pairs = [];
    for ($i = $startIdx + 1; $i < $size; ++$i) {
        $pairs[] = [$galaxies[$startIdx], $galaxies[$i]];
    }
    return empty($pairs) ? [] : [...$pairs, ...get_pairs($galaxies, $size, $startIdx + 1)];
}

function get_path_length(array $pair): int
{
    return abs($pair[0][0] - $pair[1][0]) + abs($pair[0][1] - $pair[1][1]);
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$galaxies = [];
foreach ($lines as $x => $line) {
    foreach (str_split($line) as $y => $char) {
        if ($char === '#') {
            $galaxies[] = [$x, $y];
        }
    }
}

// expand
$expandedGalaxy = [...$galaxies];
foreach ($lines as $x => $line) {
    if ($line === str_repeat('.', strlen($line))) {
        foreach ($galaxies as $idx => $galaxy) {
            if ($galaxy[0] > $x) {
                $expandedGalaxy[$idx][0]++;
            }
        }
    }
}

for ($y = 0; $y < strlen($lines[0]); ++$y)
{
    $column = '';
    foreach ($lines as $line) {
        $column .= $line[$y];
    }
    if ($column === str_repeat('.', count($lines))) {
        foreach ($galaxies as $idx => $galaxy) {
            if ($galaxy[1] > $y) {
                $expandedGalaxy[$idx][1]++;
            }
        }
    }
}

$total = 0;
$pairs = get_pairs($expandedGalaxy, count($expandedGalaxy));
foreach ($pairs as $pair) {
    $total += get_path_length($pair);
}

var_dump($total);
