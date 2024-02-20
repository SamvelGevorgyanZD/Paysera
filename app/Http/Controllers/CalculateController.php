<?php

namespace App\Http\Controllers;

use App\Services\DepositService;
use App\Services\WithdrawService;
use Illuminate\Support\Facades\Storage;

class CalculateController extends Controller
{
    public function index(string $path)
    {
        $response = [];
        $json = json_decode(Storage::get($path));
        foreach ($json as $data) {
            if ($data->operation == "deposit") {
                $response[] = DepositService::handle($data);
            } else if ($data->operation == "withdraw") {
                $response[] = WithdrawService::handle($data);
            }
        }
        return response()->json($response);
    }
}
