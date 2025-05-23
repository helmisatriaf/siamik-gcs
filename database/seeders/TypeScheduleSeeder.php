<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TypeScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_schedules')->insert([
            [
                'name' => 'Event',
                'color' => 'indigo',
                'created_at' => now(),
            ],
            [
                'name' => 'National Day',
                'color' => 'red',
                'created_at' => now(),
            ],
            [
                'name' => 'Lesson',
                'color' => 'magenta',
                'created_at' => now(),
            ],
            [
                'name' => 'Mid Exam',
                'color' => 'gray',
                'created_at' => now(),
            ],
            [
                'name' => 'Final Exam',
                'color' => 'purple',
                'created_at' => now(),
            ],
        ]);
    }
}
