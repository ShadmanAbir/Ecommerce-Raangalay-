<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'quantity',
        'price',
        'expiry_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
