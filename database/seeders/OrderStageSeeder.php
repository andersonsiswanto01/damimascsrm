<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStage;

class OrderStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            ['code' => 'shipped', 'name' => 'Barang Telah Dikirim', 'is_final' => true],
            ['code' => 'canceled', 'name' => 'Dibatalkan', 'is_final' => true],
        ];
    
        foreach ($stages as $stage) {
            OrderStage::updateOrCreate(['code' => $stage['code']], $stage);
        }
    }
}
