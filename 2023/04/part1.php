<?php

$total = 0;
$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $line) {
    if (!preg_match('/Card.*:(.*)\|(.*)/', $line, $matches)) {
        continue;
    }
    preg_match_all('/([\d]+)/', $matches[1], $winningCards);
    preg_match_all('/([\d]+)/', $matches[2], $currentCards);
    $currentWinningCards = array_intersect($winningCards[1], $currentCards[1]);
    if (!empty($currentWinningCards)) {
        $total += pow(2, count($currentWinningCards) - 1);
    }
}

var_dump($total);
