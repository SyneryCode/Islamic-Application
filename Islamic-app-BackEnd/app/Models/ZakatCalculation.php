<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatCalculation extends Model
{
    protected $fillable = [
        'zakat_type_id',
        'amount',
        'nisab_value',
        'zakat_value',
        'details'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    public function type()
    {
        return $this->belongsTo(ZakatType::class, 'zakat_type_id');
    }
}