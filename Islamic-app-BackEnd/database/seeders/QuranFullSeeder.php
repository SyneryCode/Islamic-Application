<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class QuranFullSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/quran_full.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("❌ quran_full.json غير موجود!");
            return;
        }

        $verses = json_decode(File::get($jsonPath), true);

        DB::table('verses')->truncate();

        $batch = [];
        $count = 0;

        foreach ($verses as $v) {
            $batch[] = [
                'surah_id'     => $v['surah'],
                'verse_number' => $v['ayah'],
                'text_ar'      => $v['text'],
                'page_number'  => $v['page'],
                'juz_number'   => $v['juz'],
                'hizb_number'  => $v['hizb'],
                'hizb_quarter' => $v['quarter'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            if (count($batch) >= 500) {
                DB::table('verses')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('verses')->insert($batch);
        }

        $this->command->info("✅ تم إدخال {$count} آية بنجاح (القرآن كامل)!");
    }
}
