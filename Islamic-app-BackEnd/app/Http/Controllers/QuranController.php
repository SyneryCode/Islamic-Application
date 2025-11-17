<?php

namespace App\Http\Controllers;

use App\Services\QuranService;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function __construct(private QuranService $quranService) {}

    public function surah(int $surahId)
    {
        return response()->json($this->quranService->getSurah($surahId));
    }

    public function verse(string $reference)
    {
        [$surahId, $verseNumber] = explode(':', $reference);
        return response()->json($this->quranService->getVerse((int)$surahId, (int)$verseNumber));
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');
        if (!$keyword) {
            return response()->json(['error' => 'Missing query parameter q'], 400);
        }
        return response()->json($this->quranService->search($keyword));
    }
}