<?php

enum Direction: int
{
    case UP = 1;
    case DOWN = 2;
    case LEFT = 3;
    case RIGHT = 4;
}

function move_rocks(array &$map, int $size, Direction $direction): void
{
    if ($direction !== Direction::DOWN) {
        for ($x = 0; $x < $size; ++$x) {
            if ($direction === Direction::RIGHT) {
                for ($y = $size - 1; $y >= 0; --$y) {
                    if ($map[$x][$y] === '#' || $map[$x][$y] === 'O') {
                        continue;
                    }
                    for ($i = $y - 1; $i >= 0 && $map[$x][$i] !== '#'; --$i) {
                        if ($map[$x][$i] === '.') {
                            continue;
                        }
                        $map[$x][$y] = 'O';
                        $map[$x][$i] = '.';
                        break;
                    }
                }
            } else {
                for ($y = 0; $y < $size; ++$y) {
                    if ($map[$x][$y] === '#' || $map[$x][$y] === 'O') {
                        continue;
                    }
                    if ($direction === Direction::LEFT) {
                        for ($i = $y + 1; $i < $size && $map[$x][$i] !== '#'; ++$i) {
                            if ($map[$x][$i] === '.') {
                                continue;
                            }
                            $map[$x][$y] = 'O';
                            $map[$x][$i] = '.';
                            break;
                        }
                    } else {
                        for ($i = $x + 1; $i < $size && $map[$i][$y] !== '#'; ++$i) {
                            if ($map[$i][$y] === '.') {
                                continue;
                            }
                            $map[$x][$y] = 'O';
                            $map[$i][$y] = '.';
                            break;
                        }
                    }
                }
            }
        }
    } else {
        for ($x = $size - 1; $x >= 0; --$x) {
            for ($y = 0; $y < $size; ++$y) {
                if ($map[$x][$y] === '#' || $map[$x][$y] === 'O') {
                    continue;
                }
                for ($i = $x - 1; $i >= 0 && $map[$i][$y] !== '#'; --$i) {
                    if ($map[$i][$y] === '.') {
                        continue;
                    }
                    $map[$x][$y] = 'O';
                    $map[$i][$y] = '.';
                    break;
                }
            }
        }
    }
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = [];
foreach ($lines as $line) {
    $map[] = str_split($line);
}
$size = count($map);
$total = 0;
$cache = [];
$cycles = 1000000000;
for ($i = 0; $i < $cycles; ++$i) {
    $key = sha1(json_encode($map));
    if (isset($cache[$key])) {
        $cache = array_slice($cache, array_search($key, array_keys($cache)));
        $newKey = array_keys($cache)[($cycles - $i - 1) % count($cache)];
        $map = json_decode($cache[$newKey]);
        break;
    }
    move_rocks($map, $size, Direction::UP);
    move_rocks($map, $size, Direction::LEFT);
    move_rocks($map, $size, Direction::DOWN);
    move_rocks($map, $size, Direction::RIGHT);
    $json = json_encode($map);
    $cache[$key] = $json;
}

foreach ($map as $x => $row) {
    foreach ($row as $item) {
        if ($item === 'O') {
            $total += $size - $x;
        }
    }
}

var_dump($total);
