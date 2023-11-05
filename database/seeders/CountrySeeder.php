<?php

namespace Database\Seeders;

use App\Models\Location\City\City;
use App\Models\Location\Country\Country;
use App\Models\Location\State\State;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for($i = 0 ;$i <= 2;$i++){
            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $data[$localeCode] = ['title' => $faker->text(20),

              ];
            }
            Country::create($data);
        }
        $countries = collect(Country::all()->modelKeys());
        foreach ($countries as $country) {
            for ($e = 1; $e <= 3; $e++) {
            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $data2[$localeCode] = ['title' => $faker->text(20),

                ];
            }
            $data2["country_id"] = $country;
            State::create($data2);
        }
       }
    //    $states = State::all();

    //    foreach ($states as $state) {
    //        for ($e = 1; $e <= 3; $e++) {
    //        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
    //            $data3[$localeCode] = ['title' => $faker->text(20),

    //            ];
    //        }
    //        $data3["country_id"] = $state->country_id;
    //        $data3["state_id"] = $state->id;
    //        City::create($data3);
    //    }
    //    }
    }
}
