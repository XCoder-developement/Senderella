<?php

namespace Database\Seeders;

use App\Models\FamilyValue\FamilyValue;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FamilyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        for($i = 0 ; $i < 3 ; $i++){
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['title' => $faker->text(20),
          ];
        }
        FamilyValue::create($data);
    }
}
}
