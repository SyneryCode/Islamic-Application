<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Reciter extends Model
{
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'style',
    ];

    // إذا أردت لاحقًا طريقة مساعدة لبناء base URL من الكود:
    public function getBaseAudioUrlAttribute(): string
    {
        // تنسيق ثابت نستخدمه في الخدمة (يمكن تغييره لاحقًا)
        return "https://cdn.islamic.network/quran/audio/128/{$this->code}";
    }
}
