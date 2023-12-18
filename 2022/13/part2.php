<?php

function compare_packets(null|array|int $p1, null|array|int $p2): int
{
    if ($p1 === null || $p2 === null) {
        return $p1 === null ? -1 : 1;
    }
    if (is_int($p1) && is_int($p2)) {
        return $p1 <=> $p2;
    }
    if (is_int($p1)) {
        $p1 = [$p1];
    }
    if (is_int($p2)) {
        $p2 = [$p2];
    }
    for ($i = 0; $i < max(count($p1), count($p2)); ++$i) {
        if (($cmp = compare_packets($p1[$i] ?? null, $p2[$i] ?? null))) {
            return $cmp;
        }
    }

    return 0;
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$packets = array_filter($lines, fn(string $line) => !empty(trim($line)));
$packets[] = '[[2]]';
$packets[] = '[[6]]';

usort($packets, fn(string $p1, string $p2) => compare_packets(json_decode($p1), json_decode($p2)));
var_dump((array_search('[[2]]', $packets) + 1) * (array_search('[[6]]', $packets) + 1));
