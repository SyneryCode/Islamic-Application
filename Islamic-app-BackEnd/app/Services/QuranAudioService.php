<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuranAudioService
{
    /**
     * المصادر لكل آية وسورة
     */
    private array $sources = [
        'ayah' => [
            64 => [
                'https://api.alquran.cloud/v1/audio/ayah/' // مصدر API موثوق
            ],
            128 => [
                'https://api.alquran.cloud/v1/audio/ayah/'
            ],
        ],
        'surah' => [
            'ar.alafasy' => [
                'https://api.alquran.cloud/v1/audio/surah/ar.alafasy',
                'https://quranicaudio.com/alafasy' // مصدر احتياطي
            ],
            'ar.abdulbasit' => [
                'https://api.alquran.cloud/v1/audio/surah/ar.abdulbasit',
                'https://quranicaudio.com/abdulbasit'
            ],
            // يمكن إضافة بقية القراء هنا
        ]
    ];

<<<<<<< Updated upstream
    /**
     * جلب صوت آية منفصلة مع fallback
     */
    public function getAyahAudio(int $surah, int $ayah, int $quality = 64)
    {
        $quality = in_array($quality, [64, 128]) ? $quality : 64;
        $cacheKey = "ayah_audio_{$surah}_{$ayah}_{$quality}";

        return Cache::remember($cacheKey, 3600, function() use ($surah, $ayah, $quality) {
            foreach ($this->sources['ayah'][$quality] as $baseUrl) {
                $url = "{$baseUrl}{$surah}:{$ayah}/{$quality}";
                if ($this->urlExists($url)) {
                    return [
                        'type' => 'ayah',
                        'surah' => $surah,
                        'ayah' => $ayah,
                        'quality' => $quality,
                        'audio_url' => $url
                    ];
                }
            }

            return [
                'error' => 'Audio not found',
                'message' => 'لم يتم العثور على مقطع الآية، حاول اختيار جودة أخرى أو قارئ مختلف.'
            ];
        });
    }
=======
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

>>>>>>> Stashed changes

    /**
     * جلب صوت سورة كاملة مع fallback
     */
    public function getSurahAudio(int $surah, string $reciterCode)
    {
        $cacheKey = "surah_audio_{$surah}_{$reciterCode}";

        return Cache::remember($cacheKey, 3600, function() use ($surah, $reciterCode) {
            $reciter = DB::table('reciters')->where('code', $reciterCode)->first();

            if (!$reciter) {
                return [
                    'error' => 'Invalid reciter',
                    'message' => 'القارئ غير موجود أو لم يتم إعداد الكود الصوتي.'
                ];
            }

            $surahPadded = str_pad($surah, 3, '0', STR_PAD_LEFT);
            $sources = $this->sources['surah'][$reciterCode] ?? [];

            foreach ($sources as $baseUrl) {
                $url = "{$baseUrl}/{$surahPadded}.mp3";
                if ($this->urlExists($url)) {
                    return [
                        'type' => 'surah',
                        'surah' => $surah,
                        'reciter' => $reciterCode,
                        'audio_url' => $url
                    ];
                }
            }

            return [
                'error' => 'Audio not found',
                'message' => 'لم يتم العثور على مقطع السورة، يرجى تجربة قارئ آخر.'
            ];
        });
    }

    /**
     * تحقق من وجود الرابط
     */
    private function urlExists(string $url): bool
    {
        try {
            $response = Http::withOptions(['verify' => false, 'timeout' => 5])->head($url);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
