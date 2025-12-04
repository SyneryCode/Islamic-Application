<?php
// app/Models/User.php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use  HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'reset_token',
        'reset_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'reset_token',
    ];

    protected $casts = [
        'reset_token_expires_at' => 'datetime',
    ];
public function userHabits(): HasMany
{
    return $this->hasMany(UserHabit::class);
}

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function generateResetToken(): string
    {
        $token = bin2hex(random_bytes(32));
        
        $this->update([
            'reset_token' => $token,
            'reset_token_expires_at' => now()->addHours(1),
        ]);

        return $token;
    }

    public function isResetTokenValid(): bool
    {
        return $this->reset_token && 
               $this->reset_token_expires_at && 
               $this->reset_token_expires_at->isFuture();
    }

    public function clearResetToken(): void
    {
        $this->update([
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ]);
    }
}