<?php

namespace Database\Seeders;

use App\Models\ComfortCategory;
use Illuminate\Database\Seeder;

class ComfortCategorySeeder extends Seeder
{
    public function run(): void
    {
        ComfortCategory::insert([
            ['title' => 'Первая'],
            ['title' => 'Вторая'],
            ['title' => 'Третья'],
        ]);
    }
}
