<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'order_id',
        'gateway',
        'gateway_transaction_id',
        'payment_type',
        'payment_method',
        'amount',
        'currency',
        'status',
        'fraud_status',
        'payload',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payload' => 'json',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
