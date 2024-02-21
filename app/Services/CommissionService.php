<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CommissionService
{
    protected Collection $data;
    public const OPERATION_TYPE_WITHDRAW = 'withdraw';
    public const OPERATION_TYPE_DEPOSIT = 'deposit';
    public const USER_TYPE_BUSINESS = 'business';
    public const USER_TYPE_PRIVATE = 'private';

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }

    public function calculate()
    {
        $fees = [];
        foreach ($this->data as $row) {
            switch ($row['operation']) {
                case self::OPERATION_TYPE_WITHDRAW:
                    $fees[] = $this->calculateWithdraw($row);
                    break;
                case self::OPERATION_TYPE_DEPOSIT:
                    $fees[] = $this->calculateDeposit($row);
                    break;
            }
        }
        return $fees;
    }

    protected function calculateWithdraw($row)
    {
        switch ($row['user_type']) {
            case self::USER_TYPE_BUSINESS:
                $commission = 0.5;
                break;
            case self::USER_TYPE_PRIVATE:
                $commission = 0.3;
                break;
        }

        if ($row['user_type'] == self::USER_TYPE_PRIVATE) {
            $previousOperationsInWeek = $this->data->filter(function ($r) use ($row) {
                if ($r['operation'] == self::OPERATION_TYPE_DEPOSIT) {
                    return false;
                }
                if ($r['user_id'] !== $row['user_id']) {
                    return false;
                }
                if ($r['id'] >= $row['id']) {
                    return false;
                }
                if (!(Carbon::parse($row['date'])->isSameWeek(Carbon::parse($r['date'])))) {
                    return false;
                }
                return true;
            });

            $freeChargeAmountRemaining = 1000;

            if ($previousOperationsInWeek->isNotEmpty()) {
                if ($previousOperationsInWeek->count() >= 3) {
                    $freeChargeAmountRemaining = 0;
                } else {
                    $totalChargeInWeek = $previousOperationsInWeek->sum(function ($r) {
                        return $r['eur'];
                    });
                    $freeChargeAmountRemaining = $totalChargeInWeek >= 1000 ? 0 : $freeChargeAmountRemaining - $totalChargeInWeek;
                }
            }

            $freeChargeAmount = min($freeChargeAmountRemaining, $row['eur']);
            $amountWithCommission = $freeChargeAmount === $row['eur'] ? 0 : $row['eur'] - $freeChargeAmount;

            $totalEuroCommission = commission($commission, $amountWithCommission);
            return $row['currency'] == 'EUR' ? $totalEuroCommission : number_format(exchange('EUR', $row['currency'], $totalEuroCommission), 2);
        } else {
            $totalEuroCommission = commission($commission, $row['eur']);
            return $row['currency'] == 'EUR' ? $totalEuroCommission : number_format(exchange('EUR', $row['currency'], $totalEuroCommission), 2);
        }
    }

    protected function calculateDeposit($row)
    {
        $totalEuroCommission = commission(0.03, $row['eur']);
        return $row['currency'] == 'EUR' ? $totalEuroCommission : number_format(exchange('EUR', $row['currency'], $totalEuroCommission), 2);
    }
}
