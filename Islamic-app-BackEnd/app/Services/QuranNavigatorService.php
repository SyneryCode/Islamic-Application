<?php

namespace App\Services;

use App\Models\Verse;
use Illuminate\Support\Facades\Cache;

class QuranNavigatorService
{
    public function getPage(int $page): array
    {
        return Cache::remember("page_$page", 86400, function () use ($page) {

            $verses = Verse::with('surah')
                ->where('page_number', $page)
                ->orderBy('surah_id')
                ->orderBy('verse_number')
                ->get();

            return [
                'page' => $page,
                'verses_count' => $verses->count(),
                'verses' => $this->formatVerses($verses)
            ];
        });
    }

    public function getJuz(int $juz): array
    {
        return Cache::remember("juz_$juz", 86400, function () use ($juz) {

            $verses = Verse::with('surah')
                ->where('juz_number', $juz)
                ->orderBy('surah_id')
                ->orderBy('verse_number')
                ->get();

            return [
                'juz' => $juz,
                'verses_count' => $verses->count(),
                'verses' => $this->formatVerses($verses)
            ];
        });
    }

    public function getHizb(int $hizb): array
    {
        return Cache::remember("hizb_$hizb", 86400, function () use ($hizb) {

            $verses = Verse::with('surah')
                ->where('hizb_number', $hizb)
                ->orderBy('surah_id')
                ->orderBy('verse_number')
                ->get();

            return [
                'hizb' => $hizb,
                'verses_count' => $verses->count(),
                'verses' => $this->formatVerses($verses)
            ];
        });
    }

    public function getQuarter(int $quarter): array
    {
        return Cache::remember("quarter_$quarter", 86400, function () use ($quarter) {

            $verses = Verse::with('surah')
                ->where('hizb_quarter', $quarter)
                ->orderBy('surah_id')
                ->orderBy('verse_number')
                ->get();

            return [
                'quarter' => $quarter,
                'verses_count' => $verses->count(),
                'verses' => $this->formatVerses($verses)
            ];
        });
    }

    private function formatVerses($verses): array
    {
        return $verses->map(function ($v) {

            return [
                'surah_id'      => $v->surah_id,
                'surah_number'  => $v->surah->number,
                'surah_name_ar' => $v->surah->name_ar,

                'verse_number'  => $v->verse_number,
                'text_ar'       => $v->text_ar,

                'page'          => $v->page_number,
                'juz'           => $v->juz_number,
                'hizb'          => $v->hizb_number,
                'quarter'       => $v->hizb_quarter,
            ];

        })->toArray();
    }

    public function nextAyah(int $surah, int $ayah): array
{
    $verse = Verse::where('surah_id', $surah)
        ->where('verse_number', '>', $ayah)
        ->orderBy('verse_number')
        ->first();

    if ($verse) {
        return $this->formatSingleVerse($verse);
    }

    // آخر آية في السورة → انتقل للسورة التالية
    $nextSurah = $surah + 1;
    $firstAyah = Verse::where('surah_id', $nextSurah)
        ->orderBy('verse_number')
        ->first();

    return $firstAyah
        ? $this->formatSingleVerse($firstAyah)
        : ['message' => 'Reached end of Quran'];
}

public function previousAyah(int $surah, int $ayah): array
{
    $verse = Verse::where('surah_id', $surah)
        ->where('verse_number', '<', $ayah)
        ->orderByDesc('verse_number')
        ->first();

    if ($verse) {
        return $this->formatSingleVerse($verse);
    }

    // أول آية → انتقل للسورة السابقة
    $prevSurah = $surah - 1;
    $lastAyah = Verse::where('surah_id', $prevSurah)
        ->orderByDesc('verse_number')
        ->first();

    return $lastAyah
        ? $this->formatSingleVerse($lastAyah)
        : ['message' => 'Reached beginning of Quran'];
}

// Helper للتنسيق
private function formatSingleVerse($v): array
{
    return [
        'surah_id' => $v->surah_id,
        'verse_number' => $v->verse_number,
        'text_ar' => $v->text_ar,
        'page' => $v->page_number,
        'juz' => $v->juz_number,
        'hizb' => $v->hizb_number,
        'quarter' => $v->hizb_quarter,
        'surah_name_ar' => $v->surah->name_ar,
    ];
}
public function surahInfo(int $id): array
{
    $surah = \App\Models\Surah::withCount('verses')->findOrFail($id);

    return [
        'id' => $surah->id,
        'number' => $surah->number,
        'name_ar' => $surah->name_ar,
        'name_en' => $surah->name_en,
        'verses_count' => $surah->verses_count,
        'revelation_place' => $surah->revelation_place
    ];
}
public function pageInfo(int $page): array
{
    $firstVerse = Verse::with('surah')
        ->where('page_number', $page)
        ->orderBy('surah_id')
        ->orderBy('verse_number')
        ->first();

    if (!$firstVerse) {
        return ['error' => 'Page not found'];
    }

    return [
        'page' => $page,
        'surah' => $firstVerse->surah->name_ar,
        'surah_id' => $firstVerse->surah_id,
        'first_verse' => $firstVerse->verse_number
    ];
}

}
