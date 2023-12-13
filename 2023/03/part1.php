<?php

$total = 0;
$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $lineIndex => $line) {
    if (!preg_match_all('/\d+/', $line, $numberMatches, PREG_OFFSET_CAPTURE)) {
        continue;
    }
    foreach ($numberMatches[0] as list($number, $startPosition)) {
        $endPosition = (int) $startPosition + strlen($number);
        for ($i = max($lineIndex - 1, 0); $i <= min($lineIndex + 1, count($lines) - 1); ++$i) {
            if (!preg_match_all('/[^0-9.]/', $lines[$i], $matches, PREG_OFFSET_CAPTURE)) {
                continue;
            }
            foreach ($matches[0] as $match) {
                $symbolPosition = $match[1];
                if ($symbolPosition >= $startPosition - 1 && $symbolPosition <= $endPosition) {
                    $total += (int) $number;
                    break;
                }
            }
        }
    }
}

var_dump($total);
