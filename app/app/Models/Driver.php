<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{

    protected $fillable = ['name', 'phone', 'car_id'];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

}
