<?php

function move_bricks_down(array &$bricks): int
{
    $moves = 0;
    $newBricks = [];
    foreach ($bricks as $idx => $brick) {
        $diff = null;
        foreach (array_merge(...$newBricks) as $previousBrick) {
            if (bricks_intersect($brick, $previousBrick)) {
                $diff = $brick[0][2] - $previousBrick[1][2] - 1;
                $brick[0][2] -= $diff;
                $brick[1][2] -= $diff;
                break;
            }
        }
        if ($diff === null) {
            $diff = $brick[0][2] - 1;
            $brick[0][2] -= $diff;
            $brick[1][2] -= $diff;
        }
        $newBricks[$brick[1][2]][$idx] = $brick;
        $bricks[$idx] = $brick;
        krsort($newBricks);
        $moves += (int) ($diff > 0);
    }
    $bricks = array_reverse(flatten_bricks($newBricks));
    return $moves;
}

function flatten_bricks(array $bricks): array
{
    $flattenBricks = [];
    foreach ($bricks as $bricksPerZ) {
        foreach ($bricksPerZ as $idx => $brick) {
            $flattenBricks[$idx] = $brick;
        }
    }
    return $flattenBricks;
}

function bricks_intersect(array $brick1, array $brick2): bool
{
    return
        ($brick1[0][0] >= $brick2[0][0] && $brick1[0][0] <= $brick2[1][0] ||
            $brick1[0][0] < $brick2[0][0] && $brick1[1][0] >= $brick2[0][0]) &&
        ($brick1[0][1] >= $brick2[0][1] && $brick1[0][1] <= $brick2[1][1] ||
            $brick1[0][1] < $brick2[0][1] && $brick1[1][1] >= $brick2[0][1]);
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$bricks = [];
foreach ($lines as $line) {
    $bricks[] = array_map(
        fn(string $positions) => array_map(fn (string $pos) => (int) $pos, explode(',', $positions)),
        explode('~', $line)
    );
}

uasort($bricks, fn(array $brick1, array $brick2) => $brick1[1][2] - $brick2[1][2]);
move_bricks_down($bricks);
$bricksPerRow = [];
foreach ($bricks as $idx => $brick) {
    $min = min($brick[0][2], $brick[1][2]);
    $max = max($brick[0][2], $brick[1][2]);
    $bricksPerRow[$min][] = $idx;
    if ($min !== $max) {
        foreach (range($min + 1, $max) as $row) {
            $bricksPerRow[$row][] = $idx;
        }
    }
}

ksort($bricksPerRow);
$total = 0;
foreach ($bricksPerRow as $row => $bricksIdx) {
    $supportingBricks = $supportedBy = [];
    foreach ($bricksIdx as $brickIdx) {
        foreach (($bricksPerRow[$row + 1] ?? []) as $nextRowBrickIdx) {
            if ($nextRowBrickIdx !== $brickIdx && bricks_intersect($bricks[$brickIdx], $bricks[$nextRowBrickIdx])) {
                $supportingBricks[$nextRowBrickIdx][] = $brickIdx;
                $supportedBy[$brickIdx][] = $nextRowBrickIdx;
            }
        }
    }
    foreach ($bricksIdx as $brickIdx) {
        if (isset($supportedBy[$brickIdx])) {
            foreach ($supportedBy[$brickIdx] as $supportedBrickIdx) {
                if (count($supportingBricks[$supportedBrickIdx]) < 2) {
                    $bricksCopy = $bricks;
                    unset($bricksCopy[$brickIdx]);
                    $total += move_bricks_down($bricksCopy);
                    continue 2;
                }
            }
        }
    }
}

var_dump($total);
