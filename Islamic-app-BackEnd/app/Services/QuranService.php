<?php

namespace App\Services;

use App\Models\Surah;
use App\Models\Verse;

class QuranService
{
    public function getSurah(int $surahId): array
    {
        $surah = Surah::with('verses')->findOrFail($surahId);
        return [
            'id' => $surah->id,
            'number' => $surah->number,
            'name_ar' => $surah->name_ar,
            'name_en' => $surah->name_en,
            'verses_count' => $surah->verses_count,
            'verses' => $surah->verses->map(fn($verse) => [
                'verse_number' => $verse->verse_number,
                'text_ar' => $verse->text_ar
            ])
        ];
    }

    public function getVerse(int $surahId, int $verseNumber): array
    {
        $verse = Verse::with('surah')
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
        // إزالة الأحرف الخاصة التي قد تُعطّل الـ FULLTEXT
        $keyword = preg_replace('/[+#<>]/', '', $keyword);

        $verses = Verse::with('surah')
            ->whereRaw("MATCH(text_ar) AGAINST(? IN NATURAL LANGUAGE MODE)", [$keyword])
            ->limit(50)
            ->get();

        return $verses->map(fn($verse) => [
            'surah_id' => $verse->surah_id,
            'surah_name' => $verse->surah->name_ar,
            'verse_number' => $verse->verse_number,
            'text_ar' => $verse->text_ar
        ])->toArray();
    }
}