<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('teachers')->insert([
         [
            'is_active' => 1,
            'user_id' => 4,
            'unique_id' => date('Ym') . '0001',
            'name' => 'Awan Santoso',
            'nik' => '1234567890123456',
            'religion' => 'Islam',
            'gender' => 'Male',
            'nationality' => 'Indonesia',
            'place_birth' => 'Surabaya',
            'date_birth' => '1978-04-12',
            'home_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'temporary_address' => 'Desa paku, Kecamatan Menganti, Kabupaten Gresik',
            'handphone' => '082141311214',
            'email' => 'dummy1@email.com',
            'last_education' => 'bachelor degree',
            'major' => 'Informatics',
            'created_at' => date("Y-m-d"),
         ],
      ]);
    }
}