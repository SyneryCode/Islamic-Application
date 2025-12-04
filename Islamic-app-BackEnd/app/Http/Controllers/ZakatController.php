<?php

namespace App\Http\Controllers;

use App\Services\ZakatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← الاستيراد الإجباري لتجنب الخطأ

class ZakatController extends Controller
{
    public function calculate(Request $request, ZakatService $zakatService)
    {
        $validated = $request->validate([
            'type' => 'required|string|exists:zakat_types,key',
            'amount' => 'required|numeric|min:0',
            'gold_price' => 'required|numeric|min:100|max:5000',
            'fiqh_school' => 'nullable|string|in:default,hanbali,shafii',
        ]);

        // ربط المستخدم تلقائيًا إن كان مسجلًا
        if (Auth::check()) { // ← هذا هو الحل!
            $validated['user_id'] = Auth::id(); // ← هذا هو الحل!
        }

        $result = $zakatService->calculate($validated);

        if (! $result['eligible']) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }
}