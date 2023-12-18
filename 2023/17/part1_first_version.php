<?php

class PriorityQueue
{
    private array $queue = [];

    public function addWithPriority(string $key, int $priority): void
    {
        $this->queue[$key] = $priority;
    }

    public function extractMin(): array
    {
        $key = array_keys($this->queue, min($this->queue))[0];
        $ret = [$key, $this->queue[$key]];
        unset($this->queue[$key]);

        return $ret;
    }

    public function isEmpty(): bool
    {
        return empty($this->queue);
    }
}

enum Direction: int
{
    case LEFT = 0;
    case UP = 1;
    case RIGHT = 2;
    case DOWN = 3;

    public static function getNextCoords(int $x, int $y, Direction $direction): array
    {
        return [
            $x + ($direction === Direction::DOWN ? 1 : ($direction === Direction::UP ? -1 : 0)),
            $y + ($direction === Direction::RIGHT ? 1 : ($direction === Direction::LEFT ? -1 : 0)),
        ];
    }

    public static function getPerpendicularDirections(Direction $direction): array
    {
        return $direction === Direction::UP || $direction === Direction::DOWN ?
            [Direction::LEFT, Direction::RIGHT] :
            [Direction::UP, Direction::DOWN];
    }
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$map = array_map(fn(string $line) => array_map(fn(string $char) => (int) $char, str_split($line)), $lines);
$queue = new PriorityQueue();
$queue->addWithPriority('0:0:2:0', 0);
$dists = ['0:0:2:0' => 0];
$destinationX = count($map) - 1;
$destinationY = count($map[0]) - 1;
$minDist = INF;
while (!$queue->isEmpty()) {
    list($key, $dist) = $queue->extractMin();
    list($x, $y, $direction, $steps) = explode(':', $key);

    if ((int) $x === $destinationX && (int) $y === $destinationY) {
        $minDist = min($minDist, $dist);
        continue;
    }

    if ((int) $steps < 3) {
        list($newX, $newY) = Direction::getNextCoords((int) $x, (int) $y, Direction::from((int) $direction));
        $alt = isset($map[$newX][$newY]) ? $map[$newX][$newY] + (int) $dist : INF;
        $newKey = "$newX:$newY:$direction:" . (int) $steps + 1;
        if ($alt < ($dists[$newKey] ?? INF)) {
            $dists[$newKey] = $alt;
            $queue->addWithPriority($newKey, $alt);
        }
    }

    foreach (Direction::getPerpendicularDirections(Direction::from((int) $direction)) as $direction) {;
        list($newX, $newY) = Direction::getNextCoords((int) $x, (int) $y, $direction);
        $alt = isset($map[$newX][$newY]) ? $map[$newX][$newY] + (int) $dist : INF;
        $newKey = "$newX:$newY:$direction->value:1";
        if ($alt < ($dists[$newKey] ?? INF)) {
            $dists[$newKey] = $alt;
            $queue->addWithPriority($newKey, $alt);
        }
    }
}

var_dump($minDist);
