<?php

namespace App\Http\Controllers;

use App\Services\ZakatService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function zakatReport(Request $request, ZakatService $zakatService)
    {
        $request->validate([
            'period' => 'nullable|in:monthly,yearly',
        ]);

        // التحقق من تسجيل الدخول
        if (! Auth::check()) {
            return response()->json(['error' => 'يجب تسجيل الدخول لعرض التقرير'], 401);
        }

        $userId = Auth::id();

        $data = $zakatService->generateReport($userId, $request->period ?? 'yearly');

        $pdf = Pdf::loadView('reports.zakat', compact('data', 'userId'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("زكاة_التقرير_{$userId}.pdf");
    }
}