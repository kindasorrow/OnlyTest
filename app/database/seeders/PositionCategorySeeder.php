<?php

namespace Database\Seeders;

use App\Models\ComfortCategory;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionCategorySeeder extends Seeder
{
    public function run(): void
    {
        /** @var \Illuminate\Support\Collection $cats */
        $cats = ComfortCategory::pluck('id', 'title');

        Position::where('name','Директор')
            ->first()
            ->categories()
            ->attach($cats->all());

        Position::where('name','Менеджер')
            ->first()
            ->categories()
            ->attach($cats->only(['Первая','Вторая']));

        Position::where('name','Инженер')
            ->first()
            ->categories()
            ->attach($cats->only(['Вторая','Третья']));

        Position::where('name','Водитель')
            ->first()
            ->categories()
            ->attach($cats->only(['Третья']));
    }
}
