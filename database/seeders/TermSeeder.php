<?php

namespace Database\Seeders;

use App\Models\Term\Term;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $term = Term::firstOrCreate();
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $data[$localeCode] = ['text'  => $faker->text(20),
          ];
        }

        $term->update($data);
    }
}
