<?php

namespace Database\Seeders;

use App\Models\CarModel;
use App\Models\ComfortCategory;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    public function run(): void
    {
        $cat = ComfortCategory::pluck('id', 'title');

        CarModel::insert([
            ['name'=>'Skoda Octavia' , 'comfort_category_id'=>$cat['Третья']],
            ['name'=>'Toyota Camry'  , 'comfort_category_id'=>$cat['Вторая']],
            ['name'=>'BMW 5 Series'  , 'comfort_category_id'=>$cat['Первая']],
            ['name'=>'Mercedes E-Class','comfort_category_id'=>$cat['Первая']],
            ['name'=>'Volkswagen Polo','comfort_category_id'=>$cat['Третья']],
        ]);
    }
}
