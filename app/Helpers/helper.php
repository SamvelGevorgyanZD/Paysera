<?php

if (!function_exists('exchange')) {
    function exchange(string $fromCurrency, string $toCurrency, int|float $amount): int|float
    {
        $JPY = 129.53;
        $USD = 1.1497;

        if ($toCurrency == 'EUR') {
            if ($fromCurrency == 'JPY') {
                return $amount / $JPY;
            }
            if ($fromCurrency == 'USD') {
                return $amount / $USD;
            }
        } else {
            if ($fromCurrency == 'EUR') {
                if ($toCurrency == 'JPY') {
                    return $amount * $JPY;
                }
                if ($toCurrency == 'USD') {
                    return $amount * $USD;
                }
            }
        }
        return $amount;
    }
}

if (!function_exists('commission')) {
    function commission(int|float $percentage, int|float $amount): int|float|string
    {
        $result = ($percentage / 100) * ($amount);
        return number_format($result, 2);
    }
}
