<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_address',
        'status',
        'delivery_person',
        'delivered_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
