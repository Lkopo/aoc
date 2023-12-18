<?php

function move_rocks(array $map, int $size): array
{
    for ($x = 0; $x < $size; ++$x) {
        for ($y = 0; $y < $size; ++$y) {
            if ($map[$x][$y] === '#' || $map[$x][$y] === 'O') {
                continue;
            }
            for ($i = $x + 1; $i < $size && $map[$i][$y] !== '#'; ++$i) {
                if ($map[$i][$y] === '.') {
                    continue;
                }
                $map[$x][$y] = 'O';
                $map[$i][$y] = '.';
                break;
            }
        }
    }
    return $map;
}

function rotate_map(array $map): array
{
    return array_map('array_reverse', array_map(null, ...$map));
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$size = count($map);
$cache = [];
$cycles = 1000000000;
for ($i = 0; $i < $cycles; ++$i) {
    $key = sha1(json_encode($map));
    if (isset($cache[$key])) {
        $cache = array_slice($cache, array_search($key, array_keys($cache)));
        $map = $cache[array_keys($cache)[($cycles - $i - 1) % count($cache)]];
        break;
    }
    $map = move_rocks($map, $size);
    $map = move_rocks(rotate_map($map), $size);
    $map = move_rocks(rotate_map($map), $size);
    $map = move_rocks(rotate_map($map), $size);
    $map = rotate_map($map);
    $cache[$key] = $map;
}

$total = 0;
foreach ($map as $x => $row) {
    foreach ($row as $item) {
        if ($item === 'O') {
            $total += $size - $x;
        }
    }
}

var_dump($total);
