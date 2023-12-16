<?php

enum Direction: int
{
    case LEFT = 0;
    case UP = 1;
    case RIGHT = 2;
    case DOWN = 3;
}

function move_beam(array $map, array &$energizedFields, int $x, int $y, Direction $direction): void
{
    while (true) {
        $fieldKey = sprintf('%s-%s', $x, $y);
        if (!isset($map[$x][$y]) || isset($energizedFields[$fieldKey][$direction->value])) {
            return;
        }
        $energizedFields[$fieldKey][$direction->value] = 1;
        if ($direction === Direction::LEFT || $direction === Direction::RIGHT) {
            if ($map[$x][$y] === '-' || $map[$x][$y] === '.') {
                $y += $direction === Direction::LEFT ? -1 : 1;
            } elseif ($map[$x][$y] === '/') {
                $direction = $direction === Direction::LEFT ? Direction::DOWN : Direction::UP;
                $x += $direction === Direction::DOWN ? 1 : -1;
            } elseif ($map[$x][$y] === '\\') {
                $direction = $direction === Direction::LEFT ? Direction::UP : Direction::DOWN;
                $x += $direction === Direction::UP ? -1 : 1;
            } else {
                move_beam($map, $energizedFields, $x - 1, $y, Direction::UP);
                move_beam($map, $energizedFields, $x + 1, $y, Direction::DOWN);
            }
        } else {
            if ($map[$x][$y] === '|' || $map[$x][$y] === '.') {
                $x += $direction === Direction::UP ? -1 : 1;
            } elseif ($map[$x][$y] === '/') {
                $direction = $direction === Direction::UP ? Direction::RIGHT : Direction::LEFT;
                $y += $direction === Direction::RIGHT ? 1 : -1;
            } elseif ($map[$x][$y] === '\\') {
                $direction = $direction === Direction::UP ? Direction::LEFT : Direction::RIGHT;
                $y += $direction === Direction::LEFT ? -1 : 1;
            } else {
                move_beam($map, $energizedFields, $x, $y - 1, Direction::LEFT);
                move_beam($map, $energizedFields, $x, $y + 1, Direction::RIGHT);
            }
        }
    }
}

function get_fields_count_from_start(array $map, int $x, int $y, Direction $direction): int
{
    $energizedFields = [];
    move_beam($map, $energizedFields, $x, $y, $direction);
    return count($energizedFields);
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$max = 0;
$size = count($map);
foreach (array_keys($map) as $i) {
    $max = max($max, get_fields_count_from_start($map, 0, $i, Direction::DOWN));
    $max = max($max, get_fields_count_from_start($map, $i, 0, Direction::RIGHT));
    $max = max($max, get_fields_count_from_start($map, $size - 1, $i, Direction::UP));
    $max = max($max, get_fields_count_from_start($map, $i, $size - 1, Direction::LEFT));
}

var_dump($max);
