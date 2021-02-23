<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //
    protected $table = 'carts';

    protected $fillable = [
        'order_no', 'user_id', 'status', 'payment_mode', 'currency', 'total', 'receipt', 'items', 'current_currency', 'approved', 'payed_at', 'notes'
    ];

    public $timestamps = false;

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function services() {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

}
