<?php

namespace Database\Seeders;

use App\Models\User\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = Factory::create();
       for($i = 0 ; $i < 3 ; $i++){
        // $data = User::create($data)[0];

           $data ["name"] = $faker->text(50);
           $data ["password"] = Hash::make(123123123);
           $data ["email"] = $faker->text(20);
           $data ["gender"] = 1;
        //    $data ["birthday_date"] =$faker->date();
           $data ["is_married_before"] =1;
           $data ["weight"] =$faker->text(20);

         User::create($data);
    }

    }
}
