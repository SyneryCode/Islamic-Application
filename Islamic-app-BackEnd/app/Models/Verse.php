<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verse extends Model
{
    protected $fillable = [
        'surah_id',
        'verse_number',
        'text_ar',
        'page_number',
        'juz_number',
        'hizb_number',
        'hizb_quarter'
    ];

    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }
}