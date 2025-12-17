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

    public function getAyahAudio(int $surah, int $ayah, int $quality = 128)
{
    $quality = $quality === 64 ? 64 : 128;
    $cacheKey = "ayah_audio_{$surah}_{$ayah}_{$quality}";

    return Cache::rememberForever($cacheKey, function () use ($surah, $ayah, $quality) {

        if ($surah < 1 || $surah > 114) {
            return $this->error('رقم السورة غير صحيح');
        }

        $ayahCounts = [
            7,286,200,176,120,165,206,75,129,109,123,111,43,52,
            99,128,111,110,98,135,112,78,118,64,77,227,93,88,69,
            60,34,30,73,54,45,83,182,88,75,85,54,53,89,59,37,
            35,38,29,18,45,60,49,62,55,78,96,29,22,24,13,14,
            11,11,18,12,12,30,52,52,44,28,28,20,56,40,31,50,
            40,46,42,29,19,36,25,22,17,19,26,30,20,15,21,11,
            8,8,19,5,8,8,11,11,8,3,9,5,4,7,3,6,3,5,4,5,6
        ];

        if ($ayah < 1 || $ayah > $ayahCounts[$surah - 1]) {
            return $this->error('رقم الآية غير صحيح');
        }

        // حساب الرقم العالمي للآية
        $globalAyah = array_sum(array_slice($ayahCounts, 0, $surah - 1)) + $ayah;

        $padded = str_pad($globalAyah, 6, '0', STR_PAD_LEFT);

        $reciterFolder = $quality === 64
            ? 'Alafasy_64kbps'
            : 'Alafasy_128kbps';

        return [
            'type' => 'ayah',
            'surah' => $surah,
            'ayah' => $ayah,
            'quality' => $quality,
            'reciter' => 'ar.alafasy',
            'audio_url' => "https://everyayah.com/data/{$reciterFolder}/{$padded}.mp3"
        ];
    });
}

private function error(string $message)
{
    return [
        'error' => true,
        'message' => $message
    ];
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
