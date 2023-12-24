<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$hailstones = [];
foreach ($lines as $line) {
    preg_match_all('/-?\d+/', $line, $matches);
    $hailstones[] = array_map(fn(string $pos) => (int) $pos, $matches[0]);
}
$size = count($hailstones);
$min = 200000000000000;
$max = 400000000000000;
$total = 0;
for ($i = 0; $i < $size - 1; ++$i) {
    for ($j = $i + 1; $j < $size; ++$j) {
        $slope1 = $hailstones[$i][4] / $hailstones[$i][3];
        $slope2 = $hailstones[$j][4] / $hailstones[$j][3];
        if ($slope1 === $slope2) {
            continue;
        }
        // ax + b = cx + d -> x = (d - b) / (a - c), y = ax + b
        // a = slope1, b = y1 - slope1 * x1, c = slope2, d = y2 - slope2 * x2
        $x = ($hailstones[$j][1] - $slope2 * $hailstones[$j][0] - $hailstones[$i][1] + $slope1 * $hailstones[$i][0]) / ($slope1 - $slope2);
        $y = $slope1 * $x + $hailstones[$i][1] - $slope1 * $hailstones[$i][0];
        $t1 = ($x - $hailstones[$i][0]) / $hailstones[$i][3];
        $t2 = ($x - $hailstones[$j][0]) / $hailstones[$j][3];
        if ($t1 > 0 && $t2 > 0 && $x >= $min && $x <= $max && $y >= $min && $y <= $max) {
            ++$total;
        }
    }
}

var_dump($total);
