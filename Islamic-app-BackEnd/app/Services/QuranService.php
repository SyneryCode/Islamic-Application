<?php

namespace App\Services;

use App\Models\Surah;
use App\Models\Verse;
use Illuminate\Support\Facades\Cache;

class QuranService
{
    public function getSurah(int $surahNumber): array
{
    return Cache::remember("surah_{$surahNumber}", 86400, function () use ($surahNumber) {

        // اجلب السورة بحسب الحقل number (أضمن من findOrFail برقم السورة)
        $surah = Surah::where('number', $surahNumber)
            ->with(['verses' => function ($q) {
                // ضروري تضم id و surah_id بالإضافة للحقول المطلوبة
                $q->select('id', 'surah_id', 'verse_number', 'text_ar', 'page_number', 'juz_number', 'hizb_number', 'hizb_quarter')
                  ->orderBy('verse_number');
            }])
            ->firstOrFail();

        return [
            'id' => $surah->number,            // id للمستهلك هو رقم السورة عادةً
            'number' => $surah->number,
            'name_ar' => $surah->name_ar,
            'name_en' => $surah->name_en,
            'verses_count' => $surah->verses_count,
            // حول collection إلى مصفوفة بسيطة مع الحقول المطلوبة
            'verses' => $surah->verses->map(function ($v) {
                return [
                    'surah_id' => $v->surah_id,
                    'verse_number' => $v->verse_number,
                    'text_ar' => $v->text_ar,
                    'page_number' => $v->page_number,
                    'juz_number' => $v->juz_number,
                    'hizb_number' => $v->hizb_number,
                    'hizb_quarter' => $v->hizb_quarter,
                ];
            })->toArray(),
        ];
    });
}

    public function getVerse(int $surahId, int $verseNumber): array
    {
        $verse = Verse::with('surah:id,name_ar')
            ->where('surah_id', $surahId)
            ->where('verse_number', $verseNumber)
            ->firstOrFail();

        return [
            'surah_id' => $verse->surah_id,
            'surah_name' => $verse->surah->name_ar,
            'verse_number' => $verse->verse_number,
            'text_ar' => $verse->text_ar
        ];
    }

    public function search(string $keyword): array
    {
        $keyword = trim($keyword);
        $keyword = preg_replace('/[+#<>]/', '', $keyword);

        if (mb_strlen($keyword) < 2) {
            return [];
        }

        $verses = Verse::with('surah:id,name_ar')
            ->whereRaw("MATCH(text_ar) AGAINST(? IN NATURAL LANGUAGE MODE)", [$keyword])
            ->orWhere('text_ar', 'LIKE', "%$keyword%")
            ->limit(50)
            ->get();

        return $verses->map(function ($verse) {
            return [
                'surah_id' => $verse->surah_id,
                'surah_name' => $verse->surah->name_ar,
                'verse_number' => $verse->verse_number,
                'text_ar' => $verse->text_ar
            ];
        })->toArray();
    }
}
