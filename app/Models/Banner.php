<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    public const DEFAULT_CITY = 1;


    //scopes
    public function scopeActive($value)
    {
        return $value->where('status', '=', 'active');
    }
}
