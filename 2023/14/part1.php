<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$size = count($map);
$total = 0;
for ($x = 0; $x < $size; ++$x) {
    for ($y = 0; $y < $size; ++$y) {
        if ($map[$x][$y] === '#') {
            continue;
        }
        if ($map[$x][$y] === 'O') {
            $total += $size - $x;
            continue;
        }
        for ($i = $x + 1; $i < $size && $map[$i][$y] !== '#'; ++$i) {
            if ($map[$i][$y] === '.') {
                continue;
            }
            $map[$x][$y] = 'O';
            $map[$i][$y] = '.';
            $total += $size - $x;
            break;
        }
    }
}

var_dump($total);
