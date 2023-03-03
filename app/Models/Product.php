<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'sku';
    protected $fillable = [
        'shop_id',
        'name',
        'ean',
        'sku'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class)->first();
    }
}
