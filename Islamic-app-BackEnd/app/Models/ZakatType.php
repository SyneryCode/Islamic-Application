<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZakatType extends Model
{
    protected $fillable = ['key', 'name', 'description'];

    public function calculations()
    {
        return $this->hasMany(ZakatCalculation::class);
    }
}