<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$register = 1;
$nextRegister = null;
for ($cycle = 0; $cycle < 240; ++$cycle) {
    $pos = $cycle % 40;
    echo $pos >= $register - 1 && $pos <= $register + 1 ? '#' : '.';
    if ($pos === 39) {
        echo PHP_EOL;
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
