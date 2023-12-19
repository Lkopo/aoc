<?php

function get_combinations(array $part, string $workflow, array $workflows): int
{
    if ($workflow === 'R' || $workflow === 'A') {
        return $workflow === 'R' ? 0 : array_reduce($part, fn(int $total, array $range) => $total * ($range[1] - $range[0] + 1), 1);
    }
    $total = 0;
    foreach ($workflows[$workflow] as $rule) {
        if (!isset($rule['category'])) {
            return $total + get_combinations($part, $rule['target'], $workflows);
        }
        $partCopy = $part;
        if ($rule['operator'] === '<') {
            $partCopy[$rule['category']][1] = $rule['operand'] - 1;
            $part[$rule['category']][0] = $rule['operand'];
        } else {
            $partCopy[$rule['category']][0] = $rule['operand'] + 1;
            $part[$rule['category']][1] = $rule['operand'];
        }
        $total += get_combinations($partCopy, $rule['target'], $workflows);
    }
    return $total;
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$workflows = ['A' => [], 'R' => []];
foreach ($lines as $line) {
    if (empty(trim($line))) {
        continue;
    }
    if (preg_match('/(\w+){(.*)}/', $line, $matches)) {
        $rules = [];
        foreach (explode(',', $matches[2]) as $rule) {
            if (preg_match('/(\w)([<>])(\d+):(\w+)/', $rule, $ruleMatches)) {
                $rules[] = [
                    'category' => $ruleMatches[1],
                    'operator' => $ruleMatches[2],
                    'operand' => $ruleMatches[3],
                    'target' => $ruleMatches[4]
                ];
            } else {
                $rules[] = ['target' => $rule];
            }
        }
        $workflows[$matches[1]] = $rules;
    }
}

var_dump(get_combinations(['x' => [1, 4000], 'm' => [1, 4000], 'a' => [1, 4000], 's' => [1, 4000]], 'in', $workflows));
