<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    protected $fillable = ['number', 'name_ar', 'name_en', 'revelation_place', 'verses_count'];

    public function verses(): HasMany
    {
        return $this->hasMany(Verse::class, 'surah_id');
    }
}