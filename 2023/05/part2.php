<?php

class Range
{
    public function __construct(public int $min, public int $max, public int $diff = 0) {}
}

function get_new_seeds(array $seeds, array $maps): array
{
    $newSeeds = [];
    foreach ($seeds as $seed) {
        $map = reset($maps);
        $min = $seed->min;
        $max = $seed->max;
        while ($map) {
            if ($min < $map->min && $max >= $map->min) {
                $newSeeds[] = new Range($seed->min, $map->min - 1);
                $newSeeds[] = new Range($map->min - $map->diff, min($map->max, $max) - $map->diff);
                if ($max <= $map->max) {
                    break;
                } else {
                    $min = $map->max + 1;
                    $map = next($maps);
                }
            } elseif ($min >= $map->min && $min <= $map->max) {
                $newSeeds[] = new Range($min - $map->diff, min($map->max, $max) - $map->diff);
                if ($max <= $map->max) {
                    break;
                } else {
                    $min = $map->max + 1;
                    $map = next($maps);
                }
            } elseif ($min === $seed->min) {
                $map = next($maps);
            } else {
                $newSeeds[] = new Range($min, $max);
                break;
            }
        }
        if ($map === false) {
            $newSeeds[] = new Range($min, $max);
        }
    }
    sort_ranges($newSeeds);
    return $newSeeds;
}

function sort_ranges(array &$ranges): void
{
    usort($ranges, fn(Range $range1, Range $range2) => $range1->min - $range2->min);
}

$lines = file('input.txt');
$seeds = [];
foreach ($lines as $line) {
    if (str_starts_with($line, 'seeds:')) {
        preg_match_all('/(\d+ \d+)/', $line, $matches);
        foreach ($matches[0] as $pair) {
            list($number, $iterations) = explode(' ', $pair);
            $seeds[] = new Range((int) $number, (int) $number + (int) $iterations - 1);
        }
        sort_ranges($seeds);
    } elseif (preg_match('/\w+-to-\w+ map/', $line)) {
        $maps = [];
    } elseif (preg_match_all('/\d+/', $line, $matches)) {
        $iterations = (int) end($matches[0]);
        $sourceStart = (int) $matches[0][1];
        $destinationStart = (int) $matches[0][0];
        $maps[] = new Range($sourceStart, $sourceStart + $iterations - 1, $sourceStart - $destinationStart);
    } elseif (empty(trim($line)) && !empty($maps)) {
        sort_ranges($maps);
        $seeds = get_new_seeds($seeds, $maps);
    }
}

var_dump($seeds[0]->min);
