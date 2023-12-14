<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$tX = $tY = $hX = $hY = 0;
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
                $hX += $incrementors[$direction];
                if (abs($hX - $tX) > 1) {
                    $tX += $incrementors[$direction];
                    $tY = $hY;
                }
                break;
            case 'U':
            case 'D':
                $hY += $incrementors[$direction];
                if (abs($hY - $tY) > 1) {
                    $tY += $incrementors[$direction];
                    $tX = $hX;
                }
                break;
        }
        $visitedPlaces[sprintf('%s-%s', $tX, $tY)] = 1;
    }
}

var_dump(count($visitedPlaces));
