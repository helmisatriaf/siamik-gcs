<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->insert([
            [
                'name_subject' => 'Chinese',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Creative Play',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Movie Time',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Mathematics',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Lifeskill',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'English',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Science',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Bahasa Indonesia',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'PPKn',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Religion',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'IPS',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'PE',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'IT',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'General Knowledge',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Art and Craft',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Character Building',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Health Education',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Art and Design',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Calistung',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'English Speaking',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Literasi Numerasi',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'IPS (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Seni Budaya dan Prakarya (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Bahasa Jawa (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'PJOK (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'MTK (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'IPA (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Bahasa Inggris (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Bahasa Indonesia (UN)',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Religion Catholic',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Religion Buddhism',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Religion Islamic',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Religion Christian',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Chinese Lower',
                'created_at' => now(),
            ],
            [
                'name_subject' => 'Chinese Higher',
                'created_at' => now(),
            ],
        ]);
    }
}
