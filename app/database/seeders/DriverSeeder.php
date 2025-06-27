<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Driver;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        Car::all()->each(function (Car $car) use ($faker) {
            Driver::create([
                'name'   => $faker->name(),
                'phone'  => $faker->unique()->e164PhoneNumber(),
                'car_id' => $car->id,
            ]);
        });
    }
}
