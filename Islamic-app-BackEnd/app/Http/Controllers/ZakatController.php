<?php

namespace App\Http\Controllers;

use App\Models\ZakatCalculation;
use App\Models\ZakatType;
use Illuminate\Http\Request;

class ZakatController extends Controller
{
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'type'       => 'required|string',
            'amount'     => 'required|numeric|min:0',
            'gold_price' => 'required|numeric|min:0',
        ]);

        $type = ZakatType::where('key', $validated['type'])->first();

        if (!$type) {
            return response()->json([
                'message' => 'The selected type is invalid.',
                'errors'  => ['type' => ['The selected type is invalid.']]
            ], 422);
        }

        $nisab = 85 * $validated['gold_price'];

        if ($validated['amount'] < $nisab) {
            return response()->json([
                'eligible' => false,
                'message'  => 'المبلغ أقل من النصاب، لا زكاة واجبة.',
                'nisab'    => $nisab
            ]);
        }

        $zakat = $validated['amount'] * 0.025;

        $record = ZakatCalculation::create([
            'zakat_type_id' => $type->id,
            'amount'        => $validated['amount'],
            'nisab_value'   => $nisab,
            'zakat_value'   => $zakat,
            'details'       => [
                'rate'        => '2.5%',
                'calculation' => "{$validated['amount']} * 0.025",
                'gold_price'  => $validated['gold_price'],
            ]
        ]);

        return response()->json([
            'eligible'  => true,
            'zakat'     => $zakat,
            'nisab'     => $nisab,
            'record_id' => $record->id,
            'message'   => 'تم حساب الزكاة بنجاح'
        ]);
    }
}