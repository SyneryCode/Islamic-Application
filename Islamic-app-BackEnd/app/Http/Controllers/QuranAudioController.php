<?php

namespace App\Http\Controllers;

use App\Services\QuranAudioService;
use Illuminate\Http\Request;

class QuranAudioController extends Controller
{
    public function __construct(private QuranAudioService $audio) {}

    public function ayah(int $surah, int $ayah, Request $r)
    {
        $reciter = $r->query('reciter', 'ar.alafasy');
        return response()->json(
            $this->audio->getAyahAudio($surah, $ayah, $reciter)
        );
    }
}
