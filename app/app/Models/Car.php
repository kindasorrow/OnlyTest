<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Car extends Model
{

    protected $fillable = ['car_model_id', 'reg_number'];

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /* скоуп «свободен в интервале» */
    public function scopeAvailableBetween($q, Carbon $from, Carbon $to)
    {
        return $q->whereDoesntHave('trips', function ($q) use ($from, $to) {
            $q
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($from, $to) {
                    $q
                        ->whereBetween('starts_at', [$from, $to])
                        ->orWhereBetween('ends_at', [$from, $to])
                        ->orWhere(function ($q) use ($from, $to) {
                            $q
                                ->where('starts_at', '<=', $from)
                                ->where('ends_at', '>=', $to);
                        });
                });
        });
    }

}
