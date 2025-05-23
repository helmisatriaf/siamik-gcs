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
        GradeSeeder::class,
        RoleTypeSeeder::class,
        SubjectSeeder::class,
        UserSeeder::class,
        TeacherSeeder::class,
        TypeExamSeeder::class,
        TypeScheduleSeeder::class,
      ]);
    }
}