<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FridgeItem extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'expires_at',
    ];
}
