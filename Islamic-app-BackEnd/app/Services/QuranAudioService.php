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
