<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = [];
foreach ($lines as $line) {
    $map[] = str_split($line);
}
$colSize = count($map);
$rowSize = count($map[0]);
$total = 0;
for ($x = 0; $x < $rowSize; ++$x) {
    for ($y = 0; $y < $colSize; ++$y) {
        if ($map[$x][$y] === '#') {
            continue;
        }
        if ($map[$x][$y] === 'O') {
            $total += $colSize - $x;
            continue;
        }
        for ($i = $x + 1; $i < $rowSize && $map[$i][$y] !== '#'; ++$i) {
            if ($map[$i][$y] === '.') {
                continue;
            }
            $map[$x][$y] = 'O';
            $map[$i][$y] = '.';
            $total += $colSize - $x;
            break;
        }
    }
}

var_dump($total);
