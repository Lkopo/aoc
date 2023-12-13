<?php

function replace_number(string $number): string
{
    $replace = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9
    ];

    return str_replace(array_keys($replace), $replace, $number);
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    if (!preg_match('/(\d|one|two|three|four|five|six|seven|eight|nine|ten)(?>.*(\d|one|two|three|four|five|six|seven|eight|nine|ten))?/', $line, $matches)) {
        continue;
    }
    $total += (int) sprintf('%s%s', replace_number($matches[1]), replace_number(end($matches)));
}

var_dump($total);
