<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);

function calculate_possibilities(string $springs, array $damagedSprings, int $groupStart)
{
    if (empty($damagedSprings[$groupStart])) {
        if (str_contains($springs, '#')) {
            return 0;
        } else {
            return 1;
        }
    }
    if (empty($springs)) {
        return 0;
    }
    $character = $springs[0];
    $group = $damagedSprings[$groupStart];
    if ($character === '.') {
        return calculate_possibilities(substr($springs, 1), $damagedSprings, $groupStart);
    }
    if ($character === '#') {
        $subsprings = substr($springs, 0, $group);
        if (str_replace('?', '#', $subsprings) !== str_repeat('#', $group)) {
            return 0;
        }
        if (strlen($springs) === $group) {
            return $groupStart === count($damagedSprings) - 1 ? 1 : 0;
        }
        $nextCharacter = $springs[$group] ?? null;
        if (!in_array($nextCharacter, ['.', '?'])) {
            return 0;
        }
        return calculate_possibilities(substr($springs, $group + 1), $damagedSprings, $groupStart + 1);
    }
    if ($character === '?') {
        return calculate_possibilities(substr($springs, 1), $damagedSprings, $groupStart) +
            calculate_possibilities('#' . substr($springs, 1), $damagedSprings, $groupStart);
    }
    return 0;
}

$total = 0;
foreach ($lines as $line) {
    list($springs, $damagedSprings) = explode(' ', $line);
    $damagedSprings = array_map(fn($number) => (int) $number, explode(',', $damagedSprings));
    $total += calculate_possibilities($springs, $damagedSprings, 0);
}

var_dump($total);
