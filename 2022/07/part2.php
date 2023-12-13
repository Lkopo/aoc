<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$totalSizes = [];
$currentPath = '';
foreach ($lines as $line) {
    if (preg_match('/^\$ cd ([a-zA-Z]+|\.\.|\/)/', $line, $matches)) {
        $targetDir = $matches[1];
        if ($targetDir === '..') {
            $currentPath = preg_replace('/-[a-z]+$/', '', $currentPath);
        } elseif ($targetDir === '/') {
            $currentPath = '/';
        } else {
            $currentPath .= '-' . $targetDir;
        }
        $totalSizes[$currentPath] ??= 0;
    } elseif (preg_match('/^([0-9]+) .*/', $line, $matches)) {
        $totalSizes[$currentPath] += (int) $matches[1];
        $tempPath = $currentPath;
        while (preg_replace('/-[a-z]+$/', '', $tempPath) !== $tempPath) {
            $tempPath = preg_replace('/-[a-z]+$/', '', $tempPath);
            $totalSizes[$tempPath] += (int) $matches[1];
        }
    }
}

var_dump(min(array_filter($totalSizes, fn(int $value) => $value >= -40000000 + $totalSizes['/'])));
