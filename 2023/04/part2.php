<?php

function get_winning_card_copies(array &$cache, array $winningNumbersPerCard, int $cardNumber): int
{
    if (isset($cache[$cardNumber])) {
        return $cache[$cardNumber];
    }
    if (!isset($winningNumbersPerCard[$cardNumber])) {
        return $cache[$cardNumber] = 1;
    }
    $total = 1;
    for ($i = 1; $i <= $winningNumbersPerCard[$cardNumber]; ++$i) {
        $total += $cache[$cardNumber] = get_winning_card_copies($cache, $winningNumbersPerCard, $cardNumber + $i);
    }
    return $cache[$cardNumber] = $total;
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$winningNumbersPerCard = [];
foreach ($lines as $line) {
    if (!preg_match('/Card[ ]+(\d+):(.*)\|(.*)/', $line, $matches)) {
        return;
    }
    preg_match_all('/([\d]+)/', $matches[2], $winningNumbers);
    preg_match_all('/([\d]+)/', $matches[3], $currentNumbers);
    $currentWinningNumbers = array_intersect($winningNumbers[1], $currentNumbers[1]);
    $winningNumbersPerCard[(int) $matches[1]] = count($currentWinningNumbers);
}

$cache = [];
$total = 0;
foreach (array_keys($winningNumbersPerCard) as $cardNumber) {
    $total += get_winning_card_copies($cache, $winningNumbersPerCard, $cardNumber);
}

var_dump($total);
