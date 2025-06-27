<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::insert([
            ['name' => 'Директор'],
            ['name' => 'Менеджер'],
            ['name' => 'Инженер'],
            ['name' => 'Водитель'],
        ]);
    }
}
