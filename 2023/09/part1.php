<?php

function get_basis_polynomial(array $allXValues, int $i, int $x): int
{
    $basisPolynomial = 1;
    $divisor = 1;
    for ($j = 0; $j < count($allXValues); $j++) {
        if ($j == $i) {
            continue;
        }
        $basisPolynomial *= $x - $allXValues[$j];
        $divisor *= $allXValues[$i] -  $allXValues[$j];
    }
    return $basisPolynomial / $divisor;
}

function get_lagrange_interpolation_value(array $points, int $x): int
{
    $numberOfPoints = count($points);
    $basisPolynomial = [];
    $allXValues = array_keys($points);
    $allYValues = array_values($points);

    $returnFunction = 0;
    for ($i = 0; $i < $numberOfPoints; ++$i) {
        $basisPolynomial[$i] = get_basis_polynomial($allXValues, $i, $x);
        $returnFunction += $allYValues[$i] * $basisPolynomial[$i];
    }
    return $returnFunction;
}

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);
$total = 0;
foreach ($lines as $line) {
    preg_match_all('/(-)?\d+/', $line, $matches);
    $numbers = array_map(fn(string $number) => (int) $number, $matches[0]);
    $total += get_lagrange_interpolation_value($numbers, count($numbers));
}

var_dump($total);
