<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use HijriDate\HijriDate;

class ZakatCalculation extends Model
{
    protected $fillable = [
        'zakat_type_id',
        'user_id',
        'amount',
        'nisab_value',
        'zakat_value',
        'details',
        'hijri_date',
        'fiqh_school',
    ];

    protected $casts = [
        'details' => 'array',
        'hijri_date' => 'date:Y-m-d',
    ];

    public function type()
    {
        return $this->belongsTo(ZakatType::class, 'zakat_type_id');
    }

    // هل مرّ حول هجري؟
    public function isHawlCompleted(): bool
    {
        if (! $this->hijri_date) {
            return false;
        }

        $lastHijriDate = Carbon::parse($this->hijri_date);

        // تحويل التاريخ الهجري إلى غريغوري
        try {
            $hijri = new HijriDate($lastHijriDate->format('Y-m-d'));
            $lastGregorian = $hijri->toGregorian();
        } catch (\Exception $e) {
            return false;
        }

        $diffInDays = now()->diffInDays($lastGregorian);

        return $diffInDays >= 354; // سنة هجرية ≈ 354 يومًا
    }
}