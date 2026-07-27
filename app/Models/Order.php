<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    use HasFactory;

    // Table name is customer_orders in actual database
    protected $table = 'customer_orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'discount_amount',
        'vat_amount',
        'shipping_cost',
        'total_amount',
        'shipping_address_id',
        'notes',
        'coupon_id',
        'coupon_code',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . Uuid::uuid4()->toString();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
