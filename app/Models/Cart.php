<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // So kann direkt $cart->products abrufen inkl. Mengenangabe
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_items')
            ->withPivot('quantity', 'id');
    }
}
