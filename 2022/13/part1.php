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
$pairIdx = 1;
$pairs = [];
$total = 0;
foreach ($lines as $line) {
    if (empty(trim($line))) {
        continue;
    }
    $pairs[] = json_decode($line);
    if (count($pairs) < 2) {
        continue;
    }
    if (compare_packets($pairs[0], $pairs[1]) <= 0) {
        $total += $pairIdx;
    }
    ++$pairIdx;
    $pairs = [];
}

var_dump($total);
