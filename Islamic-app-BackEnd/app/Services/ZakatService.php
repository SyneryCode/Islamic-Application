<?php

namespace App\Services;

use App\Models\ZakatCalculation;
use App\Models\ZakatType;
use Milwad\LaravelHijri\Facades\Hijri;
use Carbon\Carbon;


class ZakatService
{
    // نسب الزكاة حسب المذهب ونوع الزكاة
    private const FIQH_RATES = [
        'default' => 0.025, // 2.5%
        'hanbali' => [
            'trade' => 0.025,
            'money' => 0.025,
            'assets' => 0.025,
        ],
        'shafii' => [
            'trade' => 0.025,
            'money' => 0.025,
            'assets' => 0.025,
        ],
        // يمكنك إضافة فروق لاحقًا
    ];

    public function calculate(array $data): array
    {
        $typeKey = $data['type'];
        $amount = $data['amount'];
        $goldPrice = $data['gold_price'];
        $fiqhSchool = $data['fiqh_school'] ?? 'default';
        $userId = $data['user_id'] ?? null; // للاستخدام المستقبلي

        $type = ZakatType::where('key', $typeKey)->firstOrFail();

        // تحديد نسبة الزكاة حسب المذهب
        $rate = $this->getZakatRate($fiqhSchool, $typeKey);
        $nisab = 85 * $goldPrice;

        if ($amount < $nisab) {
            return [
                'eligible' => false,
                'message' => 'المبلغ أقل من النصاب، لا زكاة واجبة.',
                'nisab' => $nisab,
                'hawl_completed' => false,
            ];
        }

        // التحقق من الحول (للمستخدمين المسجلين فقط)
        $hawlCompleted = true;
        if ($userId) {
            $lastCalculation = ZakatCalculation::where('user_id', $userId)
                ->where('zakat_type_id', $type->id)
                ->latest()
                ->first();

            $hawlCompleted = ! $lastCalculation || $lastCalculation->isHawlCompleted();
        }

        if (! $hawlCompleted) {
            return [
                'eligible' => false,
                'message' => 'لم يمر حول هجري على آخر دفعة.',
                'nisab' => $nisab,
                'hawl_completed' => false,
            ];
        }

        $zakat = $amount * $rate;

        // حفظ الحساب
        $record = ZakatCalculation::create([
            'zakat_type_id' => $type->id,
            'user_id' => $userId,
            'amount' => $amount,
            'nisab_value' => $nisab,
            'zakat_value' => $zakat,
            'fiqh_school' => $fiqhSchool,
            'hijri_date' => Hijri::convertToHijri(now())->format('Y-m-d'),
            'details' => [
                'rate' => $rate * 100 . '%',
                'calculation' => "{$amount} * {$rate}",
                'gold_price' => $goldPrice,
                'fiqh_school' => $fiqhSchool,
            ]
        ]);

        return [
            'eligible' => true,
            'zakat' => $zakat,
            'nisab' => $nisab,
            'record_id' => $record->id,
            'hawl_completed' => true,
            'message' => 'تم حساب الزكاة بنجاح'
        ];
    }

    private function getZakatRate(string $fiqhSchool, string $typeKey): float
    {
        if ($fiqhSchool === 'default') {
            return self::FIQH_RATES['default'];
        }

        return self::FIQH_RATES[$fiqhSchool][$typeKey] ?? self::FIQH_RATES['default'];
    }

    // لإنشاء تقرير PDF (سيُستدعى لاحقًا من كونترولر منفصل)
    public function generateReport(int $userId, string $period = 'yearly')
    {
        // هنا ستُستخدم حزمة مثل Barryvdh\DomPDF لاحقًا
        // سنُبقيها بسيطة الآن: إرجاع البيانات
        $query = ZakatCalculation::with('type')
            ->where('user_id', $userId);

        if ($period === 'yearly') {
            $query->whereBetween('created_at', [
                now()->startOfYear(),
                now()->endOfYear()
            ]);
        } elseif ($period === 'monthly') {
            $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        }

        return $query->get();
    }
}