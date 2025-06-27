<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $models = CarModel::pluck('id','name');

        $cars = [
            ['car_model_id'=>$models['Skoda Octavia'] , 'reg_number'=>'А123ВС199'],
            ['car_model_id'=>$models['Toyota Camry']  , 'reg_number'=>'В456КХ199'],
            ['car_model_id'=>$models['BMW 5 Series']  , 'reg_number'=>'С789ОР199'],
            ['car_model_id'=>$models['Mercedes E-Class'],'reg_number'=>'Е321ТТ199'],
            ['car_model_id'=>$models['Volkswagen Polo'],'reg_number'=>'М654УУ199'],
        ];

        Car::insert($cars);
    }
}
