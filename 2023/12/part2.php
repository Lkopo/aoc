<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);

function calculate_possibilities(array &$cache, string $springs, array $damagedSprings, int $groupStart)
{
    $key = sprintf('s:%s_g:%s', $springs, $groupStart);
    if (isset($cache[$key])) {
        return $cache[$key];
    }
    if (empty($damagedSprings[$groupStart])) {
        if (str_contains($springs, '#')) {
            return $cache[$key] = 0;
        } else {
            return $cache[$key] = 1;
        }
    }
    if (empty($springs)) {
        return $cache[$key] = 0;
    }
    $character = $springs[0];
    $group = $damagedSprings[$groupStart];
    if ($character === '.') {
        return $cache[$key] = calculate_possibilities($cache, substr($springs, 1), $damagedSprings, $groupStart);
    }
    if ($character === '#') {
        $subsprings = substr($springs, 0, $group);
        if (str_replace('?', '#', $subsprings) !== str_repeat('#', $group)) {
            return $cache[$key] = 0;
        }
        if (strlen($springs) === $group) {
            return $cache[$key] = $groupStart === count($damagedSprings) - 1 ? 1 : 0;
        }
        $nextCharacter = $springs[$group] ?? null;
        if (!in_array($nextCharacter, ['.', '?'])) {
            return $cache[$key] = 0;
        }
        return $cache[$key] = calculate_possibilities($cache, substr($springs, $group + 1), $damagedSprings, $groupStart + 1);
    }
    if ($character === '?') {
        return $cache[$key] = calculate_possibilities($cache, substr($springs, 1), $damagedSprings, $groupStart) +
            calculate_possibilities($cache, '#' . substr($springs, 1), $damagedSprings, $groupStart);
    }
    return $cache[$key] = 0;
}

$total = 0;
foreach ($lines as $line) {
    $cache = [];
    list($springs, $damagedSprings) = explode(' ', $line);
    $springs = substr(str_repeat($springs . '?', 5), 0, -1);
    $damagedSprings = array_map(
        fn($number) => (int) $number,
        explode(',', substr(str_repeat($damagedSprings . ',', 5), 0, -1))
    );
    $total += calculate_possibilities($cache, $springs, $damagedSprings, 0);
}

var_dump($total);
