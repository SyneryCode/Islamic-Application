<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class QuranAudioService
{
    private string $base = "https://cdn.islamic.network/quran/audio/64";

    public function getAyahAudio(int $surah, int $ayah, string $reciter = 'ar.alafasy')
    {
        try {
            // 3 أرقام للملفات
            $surahPadded = str_pad($surah, 3, '0', STR_PAD_LEFT);
            $ayahPadded  = str_pad($ayah, 3, '0', STR_PAD_LEFT);

            $url = "{$this->base}/{$reciter}/{$surahPadded}{$ayahPadded}.mp3";

            // تحقق أن الملف موجود فعلاً
            $response = Http::withOptions(['verify' => false])->head($url);

            if ($response->successful()) {
                return [
                    "surah" => $surah,
                    "ayah" => $ayah,
                    "reciter" => $reciter,
                    "audio_url" => $url
                ];
            }

            return ["error" => "Audio not found"];
        }

        catch (\Exception $e) {
            return ["error" => "Audio service unavailable", "details" => $e->getMessage()];
        }
    }
}
