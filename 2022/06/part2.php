<?php

$line = file('input.txt', FILE_IGNORE_NEW_LINES)[0];
$chars = [];
foreach (str_split($line) as $idx => $char) {
    $chars[] = $char;
    if (count($chars) === 14) {
        if(count(array_unique($chars)) === 14) {
            var_dump($idx + 1);
            break;
        } else {
            array_shift($chars);
        }
    }
}
