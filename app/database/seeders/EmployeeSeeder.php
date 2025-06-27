<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');
        $positions = Position::pluck('id','name');

        // 1 – фиктивный админ для логина
        Employee::create([
            'name'        => 'Иван Админ',
            'email'       => 'ivan@example.com',
            'password'    => Hash::make('password'),
            'position_id' => $positions['Директор'],
        ]);

        // + 15 случайных сотрудников
        for ($i = 0; $i < 15; $i++) {
            Employee::create([
                'name'        => $faker->name(),
                'email'       => $faker->unique()->safeEmail(),
                'password'    => Hash::make('password'),
                'position_id' => $faker->randomElement($positions),
            ]);
        }
    }
}
