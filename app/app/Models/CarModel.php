<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarModel extends Model
{

    protected $fillable = ['name', 'comfort_category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComfortCategory::class, 'comfort_category_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

}
