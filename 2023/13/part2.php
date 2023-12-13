<?php

function try_fix_mirror(string $leftEntry, string $rightEntry): bool
{
    $diff = array_diff_assoc(str_split($leftEntry), str_split($rightEntry));
    if (count($diff) === 1) {
        return true;
    }
    return false;
}

function find_reflection(array $entries): int
{
    $size = count($entries);
    for ($i = 0; $i < count($entries) - 1; ++$i) {
        $fixed = try_fix_mirror($entries[$i], $entries[$i + 1]);
        if ($entries[$i] === $entries[$i + 1] || $fixed) {
            $valid = true;
            for ($l = $i - 1, $r = $i + 1 + $i - $l; $l >= 0 && $r < $size; --$l, ++$r) {
                if ($entries[$l] === $entries[$r] || $fixed = !$fixed && try_fix_mirror($entries[$l], $entries[$r])) {
                    continue;
                }
                $valid = false;
                break;
            }
            if ($fixed && $valid) {
                return $i + 1;
            }
        }
    }
    return 0;
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$fields = [];
$i = 0;
foreach ($lines as $line) {
    if (empty(trim($line))) {
        ++$i;
        continue;
    }
    $fields[$i]['rows'][] = $line;
    foreach (str_split($line) as $idx => $char) {
        $fields[$i]['cols'][$idx] = ($fields[$i]['cols'][$idx] ?? '') . $char;
    }
}

$totals = 0;
foreach ($fields as $field) {
    if (($horizontal = find_reflection($field['rows'])) > 0) {
        $totals += $horizontal * 100;
    } elseif (($vertical = find_reflection($field['cols'])) > 0) {
        $totals += $vertical;
    }
}

var_dump($totals);
