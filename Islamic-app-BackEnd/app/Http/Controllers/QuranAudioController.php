<?php

namespace App\Http\Controllers;

use App\Services\QuranAudioService;
use Illuminate\Http\Request;

class QuranAudioController extends Controller
{
    public function __construct(private QuranAudioService $audio) {}

    /**
     * آية منفصلة
     */
    public function ayah(int $surah, int $ayah, Request $request)
    {
        $quality = (int) $request->query('quality', 64);
        return response()->json($this->audio->getAyahAudio($surah, $ayah, $quality));
    }

    /**
     * سورة كاملة
     */
    public function surah(int $surah, Request $request)
    {
        $reciter = $request->query('reciter', 'ar.alafasy');
        return response()->json($this->audio->getSurahAudio($surah, $reciter));
    }
}
