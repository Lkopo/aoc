<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    $values = [];
    foreach (explode(',', $line) as $idx => $pair) {
        list($start, $end) = explode('-', $pair);
        for ($i = $start; $i <= $end; ++$i) {
            $values[$idx][] = $i;
        }
    }

    if (count(array_intersect(...$values))) {
        $total++;
    }
}

var_dump($total);
