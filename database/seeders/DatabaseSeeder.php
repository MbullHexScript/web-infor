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
            FoundationSeeder::class,
            AcademicSeeder::class,
            ProfileSeeder::class,
            InformationSeeder::class,
            ResearchSeeder::class,
            StudentLifeSeeder::class,
            AdminOperationsSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
