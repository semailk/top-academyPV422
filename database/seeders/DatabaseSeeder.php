<?php

namespace Database\Seeders;

use App\Models\PhoneBrand;
use App\Models\User;
use App\Models\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $phoneBrands = [
          'Iphone',
          'Nokia',
          'Samsung',
          'Xiaomi',
        ];
        foreach ($phoneBrands as $phoneBrand) {
            PhoneBrand::query()->firstOrCreate([
                'name' => $phoneBrand,
            ], [
                'name' => $phoneBrand,
            ]);
        }
        User::factory()
            ->has(Phone::factory()->count(3), 'phones')
            ->count(1000)
            ->create();
    }
}
