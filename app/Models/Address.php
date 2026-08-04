<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'street',
        'house_number', 'zip_code', 'city', 'country', 'is_default'
    ];
    public function user() { return $this->belongsTo(User::class); }
}
