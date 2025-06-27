<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Authenticatable implements JWTSubject
{

    protected $fillable = ['name', 'email', 'password', 'position_id'];

    protected $hidden = ['password', 'remember_token'];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /** @inheritDoc */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();          // id сотрудника
    }

    /** @inheritDoc */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

}
