<?php

namespace App\Services;

use App\Models\Reciter;
use App\Models\Verse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QuranAudioService
{
    // مدة الكاش بالثواني (30 يوم)
    private int $cacheTtl = 60 * 60 * 24 * 30;

    /**
     * إرجاع رابط آية.
     *
     * @param int $surah
     * @param int $ayah
     * @param string $reciterCode
     * @return array
     */
    public function getAyahAudio(int $surah, int $ayah, string $reciterCode): array
    {
        $reciter = Reciter::where('code', $reciterCode)->first();

        if (!$reciter) {
            return [
                'ok' => false,
                'status' => 404,
                'message' => 'Reciter not found',
            ];
        }

        $verse = Verse::where('surah_id', $surah)
            ->where('verse_number', $ayah)
            ->first();

        if (!$verse) {
            return [
                'ok' => false,
                'status' => 404,
                'message' => 'Ayah not found',
            ];
        }

        $cacheKey = "audio:{$reciterCode}:{$surah}:{$ayah}";

        $data = Cache::remember($cacheKey, $this->cacheTtl, function () use ($reciter, $surah, $ayah) {
            // بناء رابط حسب CDN المعتمد
            // مثال: https://cdn.islamic.network/quran/audio/128/ar.alafasy/2/255.mp3
            $url = "{$reciter->base_audio_url}/{$surah}/{$ayah}.mp3";

            return [
                'ok' => true,
                'status' => 200,
                'surah' => $surah,
                'ayah' => $ayah,
                'reciter' => $reciter->code,
                'audio_url' => $url,
            ];
        });

        return $data;
    }

    /**
     * إرجاع قائمة روابط آيات لسورة كاملة.
     *
     * ترجع مصفوفة من الكائنات (واحدة لكل آية) أو خطأ.
     *
     * @param int $surah
     * @param string $reciterCode
     * @return array
     */
    public function getSurahAudio(int $surah, string $reciterCode): array
    {
        $reciter = Reciter::where('code', $reciterCode)->first();

        if (!$reciter) {
            return [
                'ok' => false,
                'status' => 404,
                'message' => 'Reciter not found',
            ];
        }

        $verseNumbers = Verse::where('surah_id', $surah)
            ->orderBy('verse_number')
            ->pluck('verse_number');

        if ($verseNumbers->isEmpty()) {
            return [
                'ok' => false,
                'status' => 404,
                'message' => 'Surah not found or has no verses',
            ];
        }

        $result = [];
        foreach ($verseNumbers as $ayah) {
            $cacheKey = "audio:{$reciterCode}:{$surah}:{$ayah}";
            // نجلب من الكاش أو نبني الرابط (لا نعيد استعلام DB داخل الحلقة)
            $item = Cache::remember($cacheKey, $this->cacheTtl, function () use ($reciter, $surah, $ayah) {
                return [
                    'surah' => $surah,
                    'ayah' => $ayah,
                    'reciter' => $reciter->code,
                    'audio_url' => "{$reciter->base_audio_url}/{$surah}/{$ayah}.mp3",
                ];
            });

            $result[] = $item;
        }

        return ['ok' => true, 'status' => 200, 'data' => $result];
    }

    /**
     * إرجاع روابط لصفحة كاملة (الآيات المرتبطة بالصفحة مرتبة)
     */
    public function getPageAudio(int $page, string $reciterCode): array
    {
        $reciter = Reciter::where('code', $reciterCode)->first();

        if (!$reciter) {
            return [
                'ok' => false,
                'status' => 404,
                'message' => 'Reciter not found',
            ];
        }

        $verses = Verse::where('page_number', $page)
            ->orderBy('surah_id')
            ->orderBy('verse_number')
            ->get(['surah_id', 'verse_number']);

        if ($verses->isEmpty()) {
            return [
                'ok' => false,
                'status' => 404,
                'message' => 'Page not found or has no verses',
            ];
        }

        $result = [];
        foreach ($verses as $v) {
            $surah = $v->surah_id;
            $ayah  = $v->verse_number;
            $cacheKey = "audio:{$reciterCode}:{$surah}:{$ayah}";
            $item = Cache::remember($cacheKey, $this->cacheTtl, function () use ($reciter, $surah, $ayah) {
                return [
                    'surah' => $surah,
                    'ayah' => $ayah,
                    'reciter' => $reciter->code,
                    'audio_url' => "{$reciter->base_audio_url}/{$surah}/{$ayah}.mp3",
                ];
            });
            $result[] = $item;
        }

        return ['ok' => true, 'status' => 200, 'data' => $result];
    }
}
