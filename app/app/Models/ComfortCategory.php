<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComfortCategory extends Model
{

    protected $fillable = ['title'];

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'position_category');
    }

    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }

}
