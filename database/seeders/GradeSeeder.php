<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
   {
      DB::table('grades')->insert([
         [
            'name' => 'Toddler',
            'class' => '1',
            'created_at' => now(),
         ],
         [
            'name' => 'Nursery',
            'class' => '1',
            'created_at' => now(),
         ],
         [
            'name' => 'Kindergarten',
            'class' => 'A',
            'created_at' => now(),
         ],
         [
            'name' => 'Kindergarten',
            'class' => 'B',
            'created_at' => now(),
         ],
         [
            'name' => 'Primary',
            'class' => '1',
            'created_at' => now(),
         ],
         [
            'name' => 'Primary',
            'class' => '2',
            'created_at' => now(),
         ],
         [
            'name' => 'Primary',
            'class' => '3',
            'created_at' => now(),
         ],
         [
            'name' => 'Primary',
            'class' => '4',
            'created_at' => now(),
         ],
         [
            'name' => 'Primary',
            'class' => '5',
            'created_at' => now(),
         ],
         [
            'name' => 'Primary',
            'class' => '6',
            'created_at' => now(),
         ],
         [
            'name' => 'Secondary',
            'class' => '1',
            'created_at' => now(),
         ],
         [
            'name' => 'Secondary',
            'class' => '2',
            'created_at' => now(),
         ],
         [
            'name' => 'Secondary',
            'class' => '3',
            'created_at' => now(),
         ],
         [
            'name' => 'IGCSE',
            'class' => '4',
            'created_at' => now(),
         ],
      ]);
   }
}