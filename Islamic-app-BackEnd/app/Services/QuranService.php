<?php

namespace App\Services;

use App\Models\Surah;
use App\Models\Verse;
use Illuminate\Support\Facades\Cache;

class QuranService
{
    public function getSurah(int $surahId): array
    {
        return Cache::remember("surah_$surahId", 86400, function () use ($surahId) {
            $surah = Surah::with('verses:surah_id,verse_number,text_ar')
                ->findOrFail($surahId);

            return [
                'id' => $surah->id,
                'number' => $surah->number,
                'name_ar' => $surah->name_ar,
                'name_en' => $surah->name_en,
                'verses_count' => $surah->verses_count,
                'verses' => $surah->verses,
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
