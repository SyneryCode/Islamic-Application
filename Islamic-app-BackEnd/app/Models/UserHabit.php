<?php

namespace App\Models;

use App\Enums\HabitStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHabit extends Model
{
    protected $fillable = [
        'user_id',
        'habit_id',
        'name',
        'duration_days',
        'start_date',
        'completed_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'completed_at' => 'datetime',
    ];

    // نرجعها تلقائياً في الـ JSON
    protected $appends = [
        'status',
        'end_date',
    ];

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * تاريخ انتهاء العادة = start_date + duration_days
     */
    protected function endDate(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            if (!isset($attributes['start_date'], $attributes['duration_days'])) {
                return null;
            }

            $start = Carbon::parse($attributes['start_date']);
            $end   = $start->clone()->addDays((int) $attributes['duration_days']);

            return $end->toDateString();
        });
    }

    /**
     * حالة العادة (قيد التنفيذ / منتهية / منجزة)
     */
    protected function status(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            // لو منجزة
            if (! empty($attributes['completed_at'])) {
                return HabitStatus::COMPLETED->value;
            }

            if (!isset($attributes['start_date'], $attributes['duration_days'])) {
                return HabitStatus::IN_PROGRESS->value;
            }

            $start = Carbon::parse($attributes['start_date']);
            $end   = $start->clone()->addDays((int) $attributes['duration_days']);
            $now   = now();

            if ($now->greaterThan($end)) {
                return HabitStatus::EXPIRED->value;
            }

            return HabitStatus::IN_PROGRESS->value;
        });
    }
}
