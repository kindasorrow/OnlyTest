<?php
// database/seeders/TripSeeder.php

namespace Database\Seeders;

use App\Models\{Trip, Car, Employee, Position};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');
        $now   = now();

        // кэш разрешённых категорий для должности
        $positionCategories = Position::with('categories:id')
            ->get()
            ->mapWithKeys(fn($p) => [$p->id => $p->categories->pluck('id')->all()]);

        // один проход — одна поездка
        foreach (Employee::all() as $emp) {

            // 0-3 поездки на каждого
            for ($i = 0, $cnt = rand(0,3); $i < $cnt; $i++) {

                // случайный интервал в ближайшие 14 дней
                $start = $faker->dateTimeBetween('+1 day', '+14 days');
                $end   = (clone $start)->modify('+'.rand(2,8).' hours');

                $allowedCarIds = Car::query()
                    ->whereHas('model', function($q) use ($positionCategories, $emp) {
                        $q->whereIn('comfort_category_id', $positionCategories[$emp->position_id] ?? []);
                    })
                    ->pluck('id');

                if ($allowedCarIds->isEmpty()) {
                    continue; // нет машины подходящей категории
                }

                Trip::create([
                    'employee_id' => $emp->id,
                    'car_id'      => $allowedCarIds->random(),
                    'starts_at'   => Carbon::instance($start),
                    'ends_at'     => Carbon::instance($end),
                    'status'      => $faker->randomElement(['planned','approved']),
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }
    }
}
