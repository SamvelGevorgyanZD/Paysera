<?php

namespace App\Services\Clients;

class BusinessService
{
    public static function handle(object $data)
    {
        return commission(0.5, $data->amount);
    }
}
