<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/Trip.php
class Trip extends Model
{

    protected $fillable = ['employee_id', 'car_id', 'starts_at', 'ends_at', 'status'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

}
