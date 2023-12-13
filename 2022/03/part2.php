<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
$compartments = [];
for ($i = 0; $i < count($lines); ++$i) {
    $compartments[] = str_split($lines[$i]);
    if (($i + 1) % 3 === 0) {
        $commonCharacter = current(array_intersect(...$compartments));
        $commonCharacterAscii = ord($commonCharacter);
        $total += $commonCharacterAscii > 90 ? $commonCharacterAscii - 96 :
            $commonCharacterAscii - 38;
        $compartments = [];
    }
}

var_dump($total);