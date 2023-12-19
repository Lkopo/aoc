<?php

function compare(int $a, int $b, string $operator): bool
{
    return $operator === '<' ? $a < $b : $a > $b;
}

function get_final_workflow(array $part, string $workflow, array $workflows): string
{
    if (empty($workflows[$workflow])) {
        return $workflow;
    }
    foreach ($workflows[$workflow] as $rule) {
        if (!isset($rule['category'])) {
            return get_final_workflow($part, $rule['target'], $workflows);
        }
        if (compare($part[$rule['category']], $rule['operand'], $rule['operator'])) {
            return get_final_workflow($part, $rule['target'], $workflows);
        }
    }
    return '';
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$workflows = ['A' => [], 'R' => []];
$parts = [];
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
    } elseif (preg_match_all('/(\w)=(\d+)/', $line, $matches, PREG_SET_ORDER)) {
        $part = [];
        foreach ($matches as $partCategories) {
            $part[$partCategories[1]] = $partCategories[2];
        }
        $parts[] = $part;
    }
}

$total = 0;
foreach ($parts as $part) {
    if (get_final_workflow($part, 'in', $workflows) === 'A') {
        $total += array_reduce($part, fn($total, $value) => $total + $value, 0);
    }
}

var_dump($total);
