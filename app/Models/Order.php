<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use function Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [

        "ecommerce_id",
        "partner_id",
        "order_partner_id",
        "delivery_date",
        "shipping_address",
        "customer_name",
        "status"
    ];

    protected $casts = [
        'delivery_date' => 'datetime:m-d-Y',
    ];

    public function items() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
