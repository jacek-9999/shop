<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'postcode',
        'country',
        'city',
        'street',
        'latitude',
        'longitude'
    ];

    public function products(): HasMany
    {
        return $this->hasMany('App\Models\Product');
    }
}
