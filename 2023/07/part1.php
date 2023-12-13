<?php

$handConfigurations = [
    'translated' => [],
    'unique' => [],
    'bids' => [],
    'ranked' => []
];
$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $line) {
    list($hand, $bid) = explode(' ', $line);
    $mapping = [
        'T' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];
    $cards = array_map(fn(string $card) => (int) str_replace(array_keys($mapping), array_values($mapping), $card), str_split($hand));
    $handConfigurations['translated'][] = $cards;
    $handConfigurations['unique'][] = array_unique($cards);
    $sortedOccurrences = array_values(array_count_values($cards));
    rsort($sortedOccurrences);
    $handConfigurations['occurrences'][] = $sortedOccurrences;
    $handConfigurations['bids'][] = (int) $bid;
}
foreach ($handConfigurations['unique'] as $idx => $uniqueCards) {
    $rank = match (count($uniqueCards)) {
        5 => 1, // high card
        4 => 2, // 1 pair
        3 => $handConfigurations['occurrences'][(int) $idx][0] + 1, // 2 pairs or 3 of kind
        2 => $handConfigurations['occurrences'][(int) $idx][0] + 2, // full house or 4 of kind
        1 => 7 // 5 of kind
    };
    $handConfigurations['ranked'][$rank][] = $idx;
}

ksort($handConfigurations['ranked']);

$total = 0;
$rankCount = 1;
foreach ($handConfigurations['ranked'] as $rankedHands) {
    usort($rankedHands, function ($a, $b) use ($handConfigurations) {
        $key = key(array_diff_assoc($handConfigurations['translated'][$a], $handConfigurations['translated'][$b]));
        return $key === null ? 0 : $handConfigurations['translated'][$a][$key] - $handConfigurations['translated'][$b][$key];
    });
    foreach ($rankedHands as $idx) {
        $total += $handConfigurations['bids'][$idx] * $rankCount++;
    }
}

var_dump($total);
