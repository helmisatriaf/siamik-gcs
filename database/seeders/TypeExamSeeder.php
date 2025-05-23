<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_exams')->insert([
            [
                'name' => 'Homework',
                'created_at' => now(),
            ],
            [
                'name' => 'Exercise',
                'created_at' => now(),
            ],
            [
                'name' => 'Quiz',
                'created_at' => now(),
            ],
            [
                'name' => 'Final Exam',
                'created_at' => now(),
            ],
            [
                'name' => 'Participation',
                'created_at' => now(),
            ],
            [
                'name' => 'Project',
                'created_at' => now(),
            ],
            [
                'name' => 'Practical',
                'created_at' => now(),
            ],
            [
                'name' => 'Small Project',
                'created_at' => now(),
            ],
            [
                'name' => 'Presentation',
                'created_at' => now(),
            ],
            [
                'name' => 'Practical Exam',
                'created_at' => now(),
            ],
            [
                'name' => 'Written Tes',
                'created_at' => now(),
            ],
            [
                'name' => 'Big Project',
                'created_at' => now(),
            ],
            [
                'name' => 'Exam',
                'created_at' => now(),
            ],
            [
                'name' => 'Final Assessment',
                'created_at' => now(),
            ],
        ]);
    }
}
