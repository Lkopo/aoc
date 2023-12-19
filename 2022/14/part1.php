<?php

function place_sand(int $x, int $y, int $maxY, array &$map, int &$count): ?bool
{
    if ($y > $maxY) {
        return null;
    }
    if (isset($map[$x][$y])) {
        return false;
    }
    if (!place_sand($x, $y + 1, $maxY, $map, $count)
        && place_sand($x - 1, $y + 1, $maxY, $map, $count) === false
        && place_sand($x + 1, $y + 1, $maxY, $map, $count) === false) {
        $map[$x][$y] = 'o';
        ++$count;
    }
    return true;
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = [];
$maxY = 0;
foreach ($lines as $line) {
    preg_match_all('/(\d+),(\d+)/', $line, $matches, PREG_SET_ORDER);
    for ($i = 0; $i < count($matches) - 1; ++$i) {
        foreach (range($matches[$i][1], $matches[$i + 1][1]) as $x) {
            foreach (range($matches[$i][2], $matches[$i + 1][2]) as $y) {
                $map[$x][$y] = 1;
                $maxY = max($maxY, $y);
            }
        }
    }
}

$prevCount = $count = 0;
do {
    $prevCount = $count;
    place_sand(500, 0, $maxY, $map, $count);
} while ($count !== $prevCount);

var_dump($count);
