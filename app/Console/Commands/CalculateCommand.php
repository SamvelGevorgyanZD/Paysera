<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalculateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate {file}';

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
        $csv = $this->argument('file');
        $rows = array_map('str_getcsv', file($csv));
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'date' => $row[0],
                'user_id' => $row[1],
                'user_type' => $row[2],
                'operation' => $row[3],
                'amount' => $row[4],
                'currency' => $row[5]
            ];
        }
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $name = Str::random() . '.json';
        Storage::put($name, $json);
        $route = route('calculate', ['path' => $name]);
        $response = Http::get($route);
        dump($response->json());
    }
}
