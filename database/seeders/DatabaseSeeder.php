<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Aku vrtačka Makita DDF484', 'INV-0001', 'active', '2022-03-14', [
                ['Výměna baterie', '2023-05-02', 1890.00],
                ['Výměna sklíčidla', '2024-09-18', 650.50],
            ]],
            ['Úhlová bruska Bosch GWS 22', 'INV-0002', 'service', '2021-06-01', [
                ['Výměna uhlíků', '2023-01-10', 320.00],
                ['Oprava vypínače', '2025-02-20', 480.00],
            ]],
            ['Pásová pila Metabo', 'INV-0003', 'active', '2020-11-23', [
                ['Pravidelná revize', '2024-11-25', 1200.00],
            ]],
            ['Kotoučová pila Festool', 'INV-0004', 'retired', '2015-04-09', [
                ['Výměna ložisek', '2019-08-07', 2400.00],
                ['Oprava motoru – neekonomická', '2022-02-15', 0],
            ]],
            ['Svářečka Kemppi', 'INV-0005', 'active', '2023-09-30', []],
        ];

        foreach ($data as [$name, $inv, $status, $purchased, $records]) {
            $eq = Equipment::create([
                'name' => $name,
                'inventory_number' => $inv,
                'status' => $status,
                'purchased_at' => $purchased,
            ]);
            foreach ($records as [$desc, $date, $cost]) {
                $eq->serviceRecords()->create([
                    'description' => $desc,
                    'serviced_at' => $date,
                    'cost' => $cost,
                ]);
            }
        }
    }
}
