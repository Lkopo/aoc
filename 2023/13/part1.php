<?php

function find_reflection(array $entries): int
{
    $previousEntries = '';
    for ($i = 0; $i < count($entries); ++$i) {
        $previousEntries .= $entries[$i];
        if ($entries[$i] === ($entries[$i + 1] ?? null)) {
            $mirroredString = implode(array_reverse(array_slice($entries, $i + 1, $i + 1)));
            if ((strlen($mirroredString) <= strlen($previousEntries) && str_ends_with($previousEntries, $mirroredString)) ||
                str_ends_with($mirroredString, $previousEntries)) {
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
