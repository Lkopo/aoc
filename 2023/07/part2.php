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
        'J' => 1,
        'Q' => 12,
        'K' => 13,
        'A' => 14
    ];
    $cards = array_map(fn(string $card) => (int) str_replace(array_keys($mapping), array_values($mapping), $card), str_split($hand));
    $handConfigurations['translated'][] = $cards;
    $handConfigurations['unique'][] = array_unique($cards);
    $sortedOccurrences = array_count_values($cards);
    arsort($sortedOccurrences);
    $handConfigurations['occurrences'][] = $sortedOccurrences;
    $handConfigurations['bids'][] = (int) $bid;
}
foreach ($handConfigurations['unique'] as $idx => $uniqueCards) {
    $rank = match (count($uniqueCards)) {
        5 => in_array(1, $uniqueCards) ? 2 : 1, // Joker: highest -> pair
        4 => in_array(1, $uniqueCards) ? 4 : 2, // Joker: pair -> 3 of kind
        3 => in_array(1, $uniqueCards) ?
            match ($handConfigurations['occurrences'][(int) $idx][1]) {
                1 => reset($handConfigurations['occurrences'][(int) $idx]) + 1 === 3 ? 5 : 6, // Joker: J4455 -> 44455 -> full house : J4445 -> 44445 -> 4 of kind
                2, 3 => 6 // Joker: JJ445 |JJJ45 -> 44445 -> 4 of kind
            } : reset($handConfigurations['occurrences'][(int) $idx]) + 1,
        2 => in_array(1, $uniqueCards) ? 7 : reset($handConfigurations['occurrences'][(int) $idx]) + 2, // Joker: J4444 -> 44444 or JJ444 -> 44444 or JJJ44 -> 44444 or JJJJ4 -> 44444 -> 5 of kind
        1 => 7
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
