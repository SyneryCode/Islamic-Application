<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecitersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reciters')->insert([
            ['code' => 'ar.alafasy',    'name_ar' => 'مشاري العفاسي', 'name_en' => 'Alafasy'],
            ['code' => 'ar.abdulbasit','name_ar' => 'عبد الباسط',     'name_en' => 'AbdulBasit'],
            ['code' => 'ar.husary',    'name_ar' => 'الحصري',        'name_en' => 'Husary'],
        ]);
    }
}
