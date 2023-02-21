<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ecommerce_item_id',
        'partner_item_id',
        'item_quantity'
    ];

    public $timestamps = false;

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
