<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Post\Post;
use App\Models\Comment\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $comments = [];
        for ($i = 0; $i < 10; $i++) {
            $data['comment'] = $faker->text(20);
            $data['user_id'] = 1;
            $data['post_id'] = 1;
            $comment = Comment::create($data);
            $comments[] = $comment;
        }
        foreach ($comments as $comment) {
            $likesCount = rand(0, 5);
            for ($i = 0; $i < $likesCount; $i++) {
                $comment->likes()->create([
                    'user_id' => 1,
                    'likeable_id' => $comment->id,
                    'likeable_type' => 'App\Models\Comment\Comment',
                ]);
            }
        }
    }
}