<?php

namespace App\Services;

class DepositService
{
    public static function handle(object $data)
    {
        return commission(0.03, $data->amount);
    }
}
