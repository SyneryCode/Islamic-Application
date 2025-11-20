<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuranAudioService
{
    private array $sources = [
        'ayah' => [
            64 => [
                'alquran'   => 'https://api.alquran.cloud/v1/ayah/',
                'qurancom'  => 'https://api.quran.com/api/v4/verses/by_key/',
            ],
            128 => [
                'alquran'   => 'https://api.alquran.cloud/v1/ayah/',
                'qurancom'  => 'https://api.quran.com/api/v4/verses/by_key/',
            ],
        ],

        'surah' => [
            'ar.alafasy' => [
                'alquran'   => 'https://api.alquran.cloud/v1/surah/ar.alafasy',
                'mp3quran'  => 'https://server8.mp3quran.net/afs',
                'qurancom'  => 'https://api.quran.com/api/v4/chapter_recitations/7?chapter=',
            ],

            'ar.abdulbasit' => [
                'alquran'   => 'https://api.alquran.cloud/v1/surah/ar.abdulbasit',
                'mp3quran'  => 'https://server7.mp3quran.net/basit',
                'qurancom'  => 'https://api.quran.com/api/v4/chapter_recitations/8?chapter=',
            ],

            'ar.husary' => [
                'alquran'   => 'https://api.alquran.cloud/v1/surah/ar.husary',
                'mp3quran'  => 'https://server9.mp3quran.net/husr',
                'qurancom'  => 'https://api.quran.com/api/v4/chapter_recitations/9?chapter=',
            ],
        ]
    ];

    public function getAyahAudio(int $surah, int $ayah, int $quality = 64)
    {
        $quality = in_array($quality, [64, 128]) ? $quality : 64;
        $cacheKey = "ayah_audio_{$surah}_{$ayah}_{$quality}";

        return Cache::remember($cacheKey, 3600, function() use ($surah, $ayah, $quality) {
            foreach ($this->sources['ayah'][$quality] as $type => $baseUrl) {

                if ($type === 'qurancom') {
                    $url = $baseUrl . "$surah:$ayah?audio=7";
                } else {
                    $url = $baseUrl . "$surah:$ayah/$quality";
                }

                if ($this->urlExists($url)) {
                    return [
                        'type' => 'ayah',
                        'surah' => $surah,
                        'ayah' => $ayah,
                        'quality' => $quality,
                        'source' => $type,
                        'audio_url' => $url,
                    ];
                }
            }

            return [
                'error' => 'Audio not found',
                'message' => 'لم يتم العثور على تلاوة للآية المطلوبة.'
            ];
        });
    }

    public function getSurahAudio(int $surah, string $reciterCode)
    {
        $cacheKey = "surah_audio_{$surah}_{$reciterCode}";

        return Cache::remember($cacheKey, 3600, function() use ($surah, $reciterCode) {
            $reciter = DB::table('reciters')->where('code', $reciterCode)->first();

            if (!$reciter) {
                return [
                    'error' => 'Invalid reciter',
                    'message' => 'القارئ غير موجود في قاعدة البيانات.'
                ];
            }

            $surahPadded = str_pad($surah, 3, '0', STR_PAD_LEFT);
            $sources = $this->sources['surah'][$reciterCode] ?? [];

            foreach ($sources as $type => $baseUrl) {

                if ($type === 'mp3quran') {
                    $url = "$baseUrl/$surahPadded.mp3";
                }
                elseif ($type === 'qurancom') {
                    $url = $baseUrl . $surah;
                }
                else {
                    $url = "$baseUrl/$surahPadded.mp3";
                }

                if ($this->urlExists($url)) {
                    return [
                        'type' => 'surah',
                        'surah' => $surah,
                        'reciter' => $reciterCode,
                        'source' => $type,
                        'audio_url' => $url,
                    ];
                }
            }

            return [
                'error' => 'Audio not found',
                'message' => 'لم يتم العثور على تلاوة للسورة المطلوبة.'
            ];
        });
    }

    private function urlExists(string $url): bool
    {
        try {
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 5
            ])->head($url);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
