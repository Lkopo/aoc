<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$totals = [];
$register = 1;
$nextRegister = null;
for ($cycle = 1; $cycle <= 220; ++$cycle) {
    if ($cycle === 20 + (int) ($cycle / 40) * 40) {
        $totals[] = $register * $cycle;
    }
    if ($nextRegister !== null) {
        $register += $nextRegister;
        $nextRegister = null;
        continue;
    }
    if (preg_match('/addx.(-?\d+)/', current($lines), $matches)) {
        $nextRegister = (int) $matches[1];
    }
    next($lines);
}

var_dump(array_sum($totals));
