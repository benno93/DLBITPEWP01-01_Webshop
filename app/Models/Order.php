<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'shipping_address_id', 'billing_address_id',
        'status', 'total_price'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Zugriff auf OrderItems (z.B. für Einzelpreise)
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Direkter Zugriff auf die Produkte in der Bestellung
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'unit_price', 'id');
    }
}
