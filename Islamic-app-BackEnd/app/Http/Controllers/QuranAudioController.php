<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuranAudioService;

class QuranAudioController extends Controller
{
    private QuranAudioService $audio;

    public function __construct(QuranAudioService $audio)
    {
        $this->audio = $audio;
    }

    public function ayah(int $surah, int $ayah, Request $request)
    {
        $reciter = $request->query('reciter', 'ar.alafasy');

        return response()->json(
            $this->audio->getAyahAudio($surah, $ayah, $reciter)
        );
    }

    public function surah(int $surah, Request $request)
    {
        $reciter = $request->query('reciter', 'ar.alafasy');

        return response()->json(
            $this->audio->getSurahAudio($surah, $reciter)
        );
    }
}
