<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Post\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $posts = [];
        for ($i = 0; $i < 10; $i++) {
            $data = [];
            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $data[$localeCode] = [
                    'post' => $faker->text(20),
                ];
            }
            $data['user_id'] = 1;
            $post = Post::create($data);
            $posts[] = $post;
        }
        foreach ($posts as $post) {
            $likesCount = rand(0, 5);
            for ($i = 0; $i < $likesCount; $i++) {
                $post->likes()->create([
                    'user_id' => 1,
                    'likeable_id' => $post->id,
                    'likeable_type' => 'App\Models\Post\Post',
                ]);
            }
        }
    }
}
