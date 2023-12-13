<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    $firstCompartment = str_split(substr($line, 0, strlen($line) / 2));
    $secondCompartment = str_split(substr($line, strlen($line) / 2));
    $commonCharacter = current(array_intersect($firstCompartment, $secondCompartment));
    $commonCharacterAscii = ord($commonCharacter);
    $total += $commonCharacterAscii > 90 ? $commonCharacterAscii - 96 :
        $commonCharacterAscii - 38;
}

var_dump($total);
