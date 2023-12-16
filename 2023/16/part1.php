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

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => str_split($line), $lines);
$energizedFields = [];
move_beam($map, $energizedFields, 0, 0, Direction::RIGHT);

var_dump(count($energizedFields));
