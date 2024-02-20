<?php

function exchange(string $currency, int|float $amount): int|float
{
    $JPY = 161.8800;
    $USD = 1.08;
    if ($currency == 'JPY') {
        return $amount / $JPY;
    }
    if ($currency == 'USD') {
        return $amount / $USD;
    }
    return 0;
}

function commission(int|float $percentage, int|float $amount): int|float|string
{
    $result = ($percentage / 100) * ($amount);
    return number_format($result, 2);
}
