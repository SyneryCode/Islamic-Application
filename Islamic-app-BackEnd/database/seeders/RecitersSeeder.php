<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecitersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reciters')->insert([
            [
                'code' => 'ar.alafasy',
                'name_ar' => 'مشاري العفاسي',
                'name_en' => 'Alafasy',
                'style' => 'murattal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ar.abdulbasit',
                'name_ar' => 'عبد الباسط',
                'name_en' => 'AbdulBasit',
                'style' => 'murattal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ar.husary',
                'name_ar' => 'الحصري',
                'name_en' => 'Husary',
                'style' => 'murattal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
