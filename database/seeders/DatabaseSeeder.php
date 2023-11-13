<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CountrySeeder::class,
            EducationTypeSeeder::class,
            ColorSeeder::class,
            ProblemTypeSeeder::class,
            HairColorSeeder::class,
            HijibTypeSeeder::class,
            MaritalStatusSeeder::class,
            ProcreationSeeder::class,
            WorkTypeSeeder::class,
            ReligiositySeeder::class,
            EleganceStyleSeeder::class,
            HealthStatusSeeder::class,
            FirstMeetSeeder::class,
            FamilyValueSeeder::class,
            MovingPlaceSeeder::class,
            PrivacySeeder::class,
            TermSeeder::class,
            QuestionSeeder::class,
            AboutSeeder::class,
            MarriageReadinessSeeder::class,
            PackageSeeder::class,
            BlockReasonSeeder::class,
            RequirmentSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            // UserSeeder::class,

        ]);
    }
}
