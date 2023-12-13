<?php

enum Direction: int
{
    case LEFT = 0;
    case UP = 1;
    case RIGHT = 2;
    case DOWN = 3;

    public static function getCoordinateAdditions(Direction $direction): array
    {
        return match ($direction) {
            Direction::LEFT => [0, -1],
            Direction::UP => [-1, 0],
            Direction::RIGHT => [0, 1],
            Direction::DOWN => [1, 0],
        };
    }

    public static function getReverseDirection(Direction $direction): Direction
    {
        return match ($direction) {
            Direction::LEFT => Direction::RIGHT,
            Direction::RIGHT => Direction::LEFT,
            Direction::UP => Direction::DOWN,
            Direction::DOWN => Direction::UP,
        };
    }
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = [];
$startX = null;
$startY = null;
foreach ($lines as $idx => $line) {
    $map[$idx] = str_split($line);
    if (($pos = array_search('S', $map[$idx])) !== false) {
        $startX = $idx;
        $startY = $pos;
    }
}

// find connecting pipes
$fromLeft = ['-', 'L', 'F'];
$fromTop = ['|', '7', 'F'];
$fromRight = ['-', 'J', '7'];
$fromBottom = ['|', 'L', 'J'];
$pipes = [];
if (in_array($map[$startX][$startY - 1] ?? null, $fromLeft)) {
    $pipes[] = Direction::LEFT->value;
}
if (in_array($map[$startX - 1][$startY] ?? null, $fromTop)) {
    $pipes[] = Direction::UP->value;
}
if (in_array($map[$startX][$startY + 1] ?? null, $fromRight)) {
    $pipes[] = Direction::RIGHT->value;
}
if (in_array($map[$startX + 1][$startY] ?? null, $fromBottom)) {
    $pipes[] = Direction::DOWN->value;
}

$transformationOptions = [
    Direction::LEFT->value => [
        Direction::UP->value => 'J',
        Direction::RIGHT->value => '-',
        Direction::DOWN->value => '7',
    ],
    Direction::UP->value => [
        Direction::RIGHT->value => 'L',
        Direction::DOWN->value => '|',
    ],
    Direction::RIGHT->value => [
        Direction::DOWN->value => 'F',
    ]
];

$traverseMap = [
    '-' => [Direction::LEFT, Direction::RIGHT],
    '|' => [Direction::UP, Direction::DOWN],
    'L' => [Direction::UP, Direction::RIGHT],
    'J' => [Direction::UP, Direction::LEFT],
    '7' => [Direction::LEFT, Direction::DOWN],
    'F' => [Direction::RIGHT, Direction::DOWN],
];

$transformedStart = $transformationOptions[reset($pipes)][end($pipes)];
$currentPipe = $transformedStart;
$currentX = $startX;
$currentY = $startY;
$direction = current($traverseMap[$transformedStart]);
$mainPipe = [];
while ($currentPipe !== 'S') {
    $directions = $traverseMap[$currentPipe];
    unset($directions[array_search(Direction::getReverseDirection($direction), $directions)]);
    $direction = reset($directions);
    list($x, $y) = Direction::getCoordinateAdditions($direction);
    $currentX += $x;
    $currentY += $y;
    $currentPipe = $map[$currentX][$currentY];
    $mainPipe[$currentX][$currentY] = 1;
}

$map[$currentX][$currentY] = $transformedStart;
$total = 0;
foreach ($map as $x => $row) {
    $inside = false;
    $startPipe = null;
    foreach ($row as $y => $cell) {
        if (isset($mainPipe[$x][$y])) {
            if ($cell === '-') {
                continue;
            }
            if ($cell === '|') {
                $inside = !$inside;
                continue;
            }
            if (in_array($cell, ['L', 'F'])) {
                $startPipe = $cell;
                continue;
            }
            if (($startPipe === 'L' && $cell === '7') || ($startPipe === 'F' && $cell === 'J')) {
                $inside = !$inside;
            }
            $startPipe = null;
        } elseif ($inside) {
            ++$total;
        }
    }
}

var_dump($total);
