<?php

namespace Database\Seeders;

use App\Models\Question\CustomerQuestion;
use App\Models\Question\Question;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {

            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $data[$localeCode] = [
                    'question' => $faker->realText(50),
                    'answer' => $faker->realText(150),
                ];
            }

            Question::create($data);
        }
    }
}
