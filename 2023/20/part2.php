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
        $module['configuration'] = $matches[2] === '%' ? 0 : [];
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
$finalSource = key(array_filter($modules, fn(array $m) => in_array('rx', $m['targets'])));
$finalSourcePressesPerSource = [...$modules[$finalSource]['configuration']];
foreach ($modules['broadcaster']['targets'] as $broadcastTarget) {
    $cache = [];
    $presses = 0;
    $finalSourceSource = null;
    $newKey = sha1(json_encode($modules));
    do {
        ++$presses;
        $key = $newKey;
        $cache[$key] = 1;
        $queue->enqueue([0, $broadcastTarget, 'broadcaster']);
        while (!$queue->isEmpty()) {
            [$pulse, $target, $source] = $queue->dequeue();
            if (!isset($modules[$target])) {
                continue;
            }
            if ($modules[$target]['type'] === '%') {
                if ($pulse === 1) {
                    continue;
                }
                $currentStatus = $modules[$target]['configuration'] ?? false;
                $modules[$target]['configuration'] = !$currentStatus;
                $nextPulse = (int) !$currentStatus;
            } else {
                if ($target === $finalSource) {
                    $finalSourceSource = $source;
                    if ($pulse === 1) {
                        $finalSourcePressesPerSource[$source] = $presses;
                    }
                }
                $modules[$target]['configuration'][$source] = $pulse;
                $nextPulse = (int) (array_unique(array_values($modules[$target]['configuration'])) !== [1]);
            }
            foreach ($modules[$target]['targets'] as $nextTarget) {
                $queue->enqueue([$nextPulse, $nextTarget, $target]);
            }
        }
        $newKey = sha1(json_encode($modules));
    } while (!isset($cache[$newKey]));
    $finalSourcePressesPerSource[$finalSourceSource] = gmp_lcm(
        $finalSourcePressesPerSource[$finalSourceSource],
        $presses - (array_flip(array_keys($cache))[$newKey])
    );
}

$lcm = 1;
foreach ($finalSourcePressesPerSource as $presses) {
    $lcm = gmp_lcm($lcm, $presses);
}

var_dump($lcm);
