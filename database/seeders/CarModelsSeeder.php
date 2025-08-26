<?php

namespace Database\Seeders;

use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $models = [
            ['name' => 'نيسان صني', 'year_range' => ['from' => 2015, 'to' => 2022]],
            ['name' => 'نيسان تيدا', 'year_range' => ['from' => 2013, 'to' => 2020]],
            ['name' => 'نيسان سنترا', 'year_range' => ['from' => 2016, 'to' => 2023]],
            ['name' => 'نيسان جوك', 'year_range' => ['from' => 2017, 'to' => 2021]],
            ['name' => 'نيسان قاشقاي', 'year_range' => ['from' => 2018, 'to' => 2023]],
            ['name' => 'نيسان باترول', 'year_range' => ['from' => 2016, 'to' => 2022]],
            ['name' => 'نيسان أرمادا', 'year_range' => ['from' => 2017, 'to' => 2023]],
            ['name' => 'نيسان بيك أب', 'year_range' => ['from' => 2015, 'to' => 2021]],
        ];
        foreach ($models as $model) {
            CarModel::create($model);
        }
    }
}
