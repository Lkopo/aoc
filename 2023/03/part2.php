<?php

$total = 0;
$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $lineIndex => $line) {
    if (!preg_match_all('/\*/', $line, $matches, PREG_OFFSET_CAPTURE)) {
        continue;
    }
    foreach ($matches[0] as $match) {
        $numbers = [];
        $symbolPosition = $match[1];
        for ($i = max($lineIndex - 1, 0); $i <= min($lineIndex + 1, count($lines) - 1); ++$i) {
            if (!preg_match_all('/\d+/', $lines[$i], $numberMatches, PREG_OFFSET_CAPTURE)) {
                continue;
            }
            foreach ($numberMatches[0] as list($number, $position)) {
                $endPosition = (int) $position + strlen($number);
                if ($symbolPosition >= $position - 1 && $symbolPosition <= $endPosition) {
                    $numbers[] = (int) $number;
                }
            }

        }
        if (count($numbers) === 2) {
            $total += array_reduce($numbers, fn(int $carry, int $number) => $carry * $number, 1);
        }
    }
}

var_dump($total);
