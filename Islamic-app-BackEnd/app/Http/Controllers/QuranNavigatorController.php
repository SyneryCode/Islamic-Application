<?php

namespace App\Http\Controllers;

use App\Services\QuranNavigatorService;
use Illuminate\Http\Request;

class QuranNavigatorController extends Controller
{
    public function __construct(private QuranNavigatorService $service) {}

    public function page(int $page)
    {
        return response()->json($this->service->getPage($page));
    }

    public function juz(int $juz)
    {
        return response()->json($this->service->getJuz($juz));
    }

    public function hizb(int $hizb)
    {
        return response()->json($this->service->getHizb($hizb));
    }

    public function quarter(int $quarter)
    {
        return response()->json($this->service->getQuarter($quarter));
    }
}
