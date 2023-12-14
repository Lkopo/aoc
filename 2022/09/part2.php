<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$coords = [
    9 => [0, 0],
    8 => [0, 0],
    7 => [0, 0],
    6 => [0, 0],
    5 => [0, 0],
    4 => [0, 0],
    3 => [0, 0],
    2 => [0, 0],
    1 => [0, 0],
    0 => [0, 0],
];
$visitedPlaces['0-0'] = 1;
$incrementors = [
    'R' => 1,
    'D' => 1,
    'L' => -1,
    'U' => -1,
];
foreach ($lines as $line) {
    list($direction, $length) = explode(' ', $line);
    for ($i = 0; $i < (int) $length; ++$i) {
        switch ($direction) {
            case 'L':
            case 'R':
                $coords[9][0] += $incrementors[$direction];
                break;
            case 'U':
            case 'D':
                $coords[9][1] += $incrementors[$direction];
                break;
        }
        for ($j = 8; $j >= 0; --$j) {
            if ($coords[$j] === $coords[$j + 1]) {
                break;
            }
            $dX = $coords[$j + 1][0] - $coords[$j][0];
            $dY = $coords[$j + 1][1] - $coords[$j][1];
            if (abs($dX) > 1 || abs($dY) > 1) {
                $coords[$j][0] += gmp_sign($dX);
                $coords[$j][1] += gmp_sign($dY);
            }
            if ($j === 0) {
                $visitedPlaces[sprintf('%s-%s', $coords[0][0], $coords[0][1])] = 1;
            }
        }
    }
}

var_dump(count($visitedPlaces));
