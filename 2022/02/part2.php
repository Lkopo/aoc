<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    $map = [
        'A' => 1,
        'B' => 2,
        'C' => 3,
    ];
    $winningTable = [
        1 => 3,
        2 => 1,
        3 => 2,
    ];
    list($enemyTurn, $myTurn) = explode(' ', $line);
    switch ($myTurn) {
        case 'X':
            $firstScore = $winningTable[$map[$enemyTurn]];
            $secondScore = 0;
            break;
        case 'Y':
            $firstScore = $map[$enemyTurn];
            $secondScore = 3;
            break;
        case 'Z':
            $firstScore = array_flip($winningTable)[$map[$enemyTurn]];
            $secondScore = 6;
            break;
    }
    $total += $firstScore + $secondScore;
}

var_dump($total);
