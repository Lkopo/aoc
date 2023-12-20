<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$modules = [];
foreach ($lines as $line) {
    preg_match('/(broadcaster|([&%])(\w+)) -> (.*)/', $line, $matches);
    $module = [];
    if ($matches[1] === 'broadcaster') {
        $name = 'broadcaster';
        $module['type'] = 'broadcaster';
    } else {
        $name = $matches[3];
        $module['type'] = $matches[2];
    }
    preg_match_all('/\w+/', $matches[4], $matches);
    $module['name'] = $name;
    $module['targets'] = $matches[0];
    $modules[$name] = $module;
}

foreach (array_filter($modules, fn(array $module) => $module['type'] === '&') as $conjunctionModule) {
    foreach (array_filter($modules, fn(array $m) => in_array($conjunctionModule['name'], $m['targets'])) as $module) {
        $modules[$conjunctionModule['name']]['configuration'][$module['name']] = 0;
    }
}

$queue = new SplQueue();
$pulses = [0 => 0, 1 => 0];
for ($i = 0; $i < 1000; ++$i) {
    $queue->enqueue([0, 'broadcaster', null]);
    ++$pulses[0];
    while (!$queue->isEmpty()) {
        [$pulse, $target, $source] = $queue->dequeue();
        if ($target === 'broadcaster') {
            foreach ($modules[$target]['targets'] as $nextTarget) {
                $queue->enqueue([$pulse, $nextTarget, $target]);
                ++$pulses[$pulse];
            }
        } else {
            if (!isset($modules[$target])) {
                continue;
            }
            if ($modules[$target]['type'] === '%') {
                if ($pulse) {
                    continue;
                }
                $currentStatus = $modules[$target]['configuration'] ?? false;
                $modules[$target]['configuration'] = !$currentStatus;
                $nextPulse = (int) !$currentStatus;
            } else {
                $modules[$target]['configuration'][$source] = $pulse;
                $nextPulse = (int) (array_unique(array_values($modules[$target]['configuration'])) !== [1]);
            }
            foreach ($modules[$target]['targets'] as $nextTarget) {
                $queue->enqueue([$nextPulse, $nextTarget, $target]);
                ++$pulses[$nextPulse];
            }
        }
    }
}

var_dump($pulses[0] * $pulses[1]);
