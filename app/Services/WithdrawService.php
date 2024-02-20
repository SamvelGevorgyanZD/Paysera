<?php

namespace App\Services;

use App\Services\Clients\BusinessService;
use App\Services\Clients\PrivateService;

class WithdrawService
{
    public static function handle(object $data)
    {
        if ($data->user_type == 'business') {
            return BusinessService::handle($data);
        } else if ($data->user_type == 'private') {
            return PrivateService::handle($data);
        }
    }
}
