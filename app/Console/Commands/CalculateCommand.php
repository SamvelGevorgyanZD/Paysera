<?php

namespace App\Console\Commands;

use App\Services\CommissionService;
use Illuminate\Console\Command;

class CalculateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates a commission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $csv = storage_path('input.csv');
        $fileData = file($csv);
        $rows = array_map('str_getcsv', $fileData);
        $data = [];
        $i = 0;
        foreach ($rows as $row) {
            $data[] = [
                'id' => $i,
                'date' => $row[0],
                'user_id' => $row[1],
                'user_type' => $row[2],
                'operation' => $row[3],
                'amount' => $row[4],
                'currency' => $row[5],
                "eur" => exchange($row[5], 'EUR', $row[4])
            ];
            $i++;
        }
        $service = new CommissionService($data);
        $fees = $service->calculate();
        $this->info(implode("\n", $fees));
    }
}
