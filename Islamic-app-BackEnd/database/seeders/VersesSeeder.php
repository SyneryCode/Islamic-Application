<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VersesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تحقق من وجود الملف
        $sqlPath = database_path('seeders/data/quran-uthmani.sql');
        if (!File::exists($sqlPath)) {
            $this->command->error("الملف غير موجود: $sqlPath");
            return;
        }

        // 2. اقرأ الملف كنص واحد (بدون تقسيم لأسطر)
        $sqlContent = File::get($sqlPath);

        // 3. قم بتنظيف المحتوى: إزالة التعليقات وبيانات قاعدة البيانات
        $sqlContent = preg_replace('/^--.*$/m', '', $sqlContent); // إزالة التعليقات
        $sqlContent = preg_replace('/^CREATE DATABASE.*$/m', '', $sqlContent);
        $sqlContent = preg_replace('/^USE.*$/m', '', $sqlContent);
        $sqlContent = preg_replace('/^DROP TABLE.*$/m', '', $sqlContent);
        $sqlContent = preg_replace('/^CREATE TABLE.*$/m', '', $sqlContent);
        $sqlContent = preg_replace('/PRIMARY KEY.*$/m', '', $sqlContent);
        $sqlContent = preg_replace('/ENGINE=.*$/m', '', $sqlContent);

        // 4. استخراج جميع سلاسل الـ VALUES
        preg_match_all('/VALUES\s*\((\d+),\s*(\d+),\s*(\d+),\s*\'((?:[^\'\\\\]|\\\\.)*)\'\)/i', $sqlContent, $matches, PREG_SET_ORDER);

        $insertedCount = 0;
        foreach ($matches as $match) {
            DB::table('verses')->insert([
                'surah_id' => (int)$match[2],
                'verse_number' => (int)$match[3],
                'text_ar' => str_replace("''", "'", $match[4]), // معالجة الـ escape
                'page_number' => null,
                'juz_number' => null,
                'hizb_number' => null,
                'hizb_quarter' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertedCount++;
        }

        $this->command->info("✅ تم إدخال {$insertedCount} آية بنجاح!");
    }
}