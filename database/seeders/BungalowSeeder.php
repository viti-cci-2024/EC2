<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BungalowSeeder extends Seeder
{
    public function run(): void
    {
        $bungalows = [];
        // 5 bungalows mer
        for ($i = 1; $i <= 5; $i++) {
            $bungalows[] = [
                'type' => 'mer',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // 5 bungalows jardin
        for ($i = 1; $i <= 5; $i++) {
            $bungalows[] = [
                'type' => 'jardin',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('bungalows')->insert($bungalows);
    }
}
