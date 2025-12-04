<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'default_duration_days',
    ];

    public function userHabits(): HasMany
    {
        return $this->hasMany(UserHabit::class);
    }
}
