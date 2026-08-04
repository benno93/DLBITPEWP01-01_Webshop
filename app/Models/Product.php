<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id', 'name', 'stock', 'price'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Direkte Verknüpfung zu den Warenkörben über die Tabelle cart_items
    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
            ->withPivot('quantity', 'id');
    }
}
