<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecitersSeeder extends Seeder
{
    public function run(): void
    {
        $reciters = [
            ['code'=>'ar.alafasy', 'mp3_code'=>'Alafasy_128kbps', 'name_ar'=>'مشاري العفاسي', 'name_en'=>'Alafasy', 'style'=>'murattal'],
            ['code'=>'ar.abdulbasit', 'mp3_code'=>'AbdulBasit_128kbps', 'name_ar'=>'عبد الباسط', 'name_en'=>'AbdulBasit', 'style'=>'murattal'],
            ['code'=>'ar.husary', 'mp3_code'=>'Husary_128kbps', 'name_ar'=>'الحصري', 'name_en'=>'Husary', 'style'=>'murattal'],
            ['code'=>'ar.minshawy', 'mp3_code'=>'Minshawy_128kbps', 'name_ar'=>'المنشاوي', 'name_en'=>'Minshawy', 'style'=>'murattal'],
            ['code'=>'ar.sudais', 'mp3_code'=>'Sudais_128kbps', 'name_ar'=>'سعودي', 'name_en'=>'Sudais', 'style'=>'murattal'],
            ['code'=>'ar.shatri', 'mp3_code'=>'Shatri_128kbps', 'name_ar'=>'الشاطري', 'name_en'=>'Shatri', 'style'=>'murattal'],
            ['code'=>'ar.hani', 'mp3_code'=>'Hani_128kbps', 'name_ar'=>'هاني', 'name_en'=>'Hani', 'style'=>'murattal'],
            ['code'=>'ar.jibreel', 'mp3_code'=>'Jibreel_128kbps', 'name_ar'=>'جبريل', 'name_en'=>'Jibreel', 'style'=>'murattal'],
            ['code'=>'ar.beshr', 'mp3_code'=>'Beshr_128kbps', 'name_ar'=>'بشر', 'name_en'=>'Beshr', 'style'=>'murattal'],
            ['code'=>'ar.taher', 'mp3_code'=>'Taher_128kbps', 'name_ar'=>'الطاهر', 'name_en'=>'Taher', 'style'=>'murattal'],
            ['code'=>'ar.muhammad', 'mp3_code'=>'Muhammad_128kbps', 'name_ar'=>'محمد', 'name_en'=>'Muhammad', 'style'=>'murattal'],
            ['code'=>'ar.ali', 'mp3_code'=>'Ali_128kbps', 'name_ar'=>'علي', 'name_en'=>'Ali', 'style'=>'murattal'],
            ['code'=>'ar.fares', 'mp3_code'=>'Fares_128kbps', 'name_ar'=>'فارس', 'name_en'=>'Fares', 'style'=>'murattal'],
            ['code'=>'ar.ahmed', 'mp3_code'=>'Ahmed_128kbps', 'name_ar'=>'أحمد', 'name_en'=>'Ahmed', 'style'=>'murattal'],
            ['code'=>'ar.khaled', 'mp3_code'=>'Khaled_128kbps', 'name_ar'=>'خالد', 'name_en'=>'Khaled', 'style'=>'murattal'],
            ['code'=>'ar.mansour', 'mp3_code'=>'Mansour_128kbps', 'name_ar'=>'منصور', 'name_en'=>'Mansour', 'style'=>'murattal'],
            ['code'=>'ar.ali2', 'mp3_code'=>'Ali2_128kbps', 'name_ar'=>'علي الثاني', 'name_en'=>'Ali2', 'style'=>'murattal'],
            ['code'=>'ar.abdullah', 'mp3_code'=>'Abdullah_128kbps', 'name_ar'=>'عبد الله', 'name_en'=>'Abdullah', 'style'=>'murattal'],
            ['code'=>'ar.fadel', 'mp3_code'=>'Fadel_128kbps', 'name_ar'=>'فاضل', 'name_en'=>'Fadel', 'style'=>'murattal'],
            ['code'=>'ar.yasir', 'mp3_code'=>'Yasir_128kbps', 'name_ar'=>'ياسر', 'name_en'=>'Yasir', 'style'=>'murattal'],
            // يمكن إضافة المزيد بسهولة هنا
        ];

        foreach ($reciters as $reciter) {
            DB::table('reciters')->updateOrInsert(
                ['code' => $reciter['code']],
                $reciter + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
