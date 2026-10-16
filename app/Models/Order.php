<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_PROCESSING = 'PROCESSING';
    public const STATUS_SHIPPED = 'SHIPPED';
    public const STATUS_DELIVERED = 'DELIVERED';
    public const STATUS_CANCELLED = 'CANCELLED';

    public const PAYMENT_PENDING = 'PENDING';
    public const PAYMENT_PAID = 'PAID';
    public const PAYMENT_FAILED = 'FAILED';
    public const PAYMENT_REFUNDED = 'REFUNDED';

    public const DELIVERY_PICKUP = 'PICKUP';
    public const DELIVERY_DELIVERY = 'DELIVERY';

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'delivery_method',
        'delivery_address',
        'customer_note',
        'placed_at',
        'confirmed_at',
        'cancelled_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'delivery_address' => 'array',
            'placed_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
        ], true);
    }
}
