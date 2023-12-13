<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    $map = [
        'A' => 1,
        'B' => 2,
        'C' => 3,
        'X' => 1,
        'Y' => 2,
        'Z' => 3,
    ];
    $winningTable = [
        1 => 3,
        2 => 1,
        3 => 2,
    ];
    list($enemyTurn, $myTurn) = explode(' ', $line);
    $firstScore = $map[$myTurn];
    if ($map[$enemyTurn] - $map[$myTurn] === 0) {
        $secondScore = 3;
    } else {
        $secondScore = $winningTable[$map[$enemyTurn]] === $map[$myTurn] ? 0 : 6;
    }
    $total += $firstScore + $secondScore;
}

var_dump($total);
