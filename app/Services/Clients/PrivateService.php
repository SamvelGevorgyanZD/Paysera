<?php

namespace App\Services\Clients;

use Illuminate\Support\Facades\Cache;

class PrivateService
{
    public static function handle(object $data)
    {
        $freeLimit = 1000;
        if ($data->currency != 'EUR') {
            $data->amount = exchange($data->currency, $data->amount);
        }
        if ($data->amount > $freeLimit) {
            return commission(0.3, $data->amount - $freeLimit);
        } else {
            if (!Cache::has($data->user_id)) {
                Cache::put($data->user_id, 1);
            }
            $count = Cache::get($data->user_id);
            if ($count < 4) {
                Cache::increment($data->user_id);
                return "0.00";
            } else {
                return commission(0.3, $data->amount);
            }
        }
    }
}
