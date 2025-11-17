<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuranTextToVersesSeeder extends Seeder
{
    public function run(): void
    {
        // نقل البيانات من quran_text إلى verses
        DB::statement("
            INSERT INTO verses (surah_id, verse_number, text_ar, page_number, juz_number, hizb_number, hizb_quarter, created_at, updated_at)
            SELECT 
                sura AS surah_id,
                aya AS verse_number,
                text AS text_ar,
                NULL AS page_number,
                NULL AS juz_number,
                NULL AS hizb_number,
                NULL AS hizb_quarter,
                NOW(),
                NOW()
            FROM quran_text
        ");

        $count = DB::table('verses')->count();
        $this->command->info("✅ تم نقل {$count} آية من quran_text إلى verses بنجاح!");
    }
}