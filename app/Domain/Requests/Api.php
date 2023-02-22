<?php

namespace App\Domain\Requests;

use App\Domain\Contracts\PartnerInterface;
use App\Domain\Types\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class Api implements PartnerInterface
{
    public function __construct(
        public Order $order,
    ) {}

    public function run() : OrderStatus
    {

        $request = $this->prepareData();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->order->partner->endpoint, $request);

        if(! $response->ok()) {
            OrderStatus::Error;
        }

        return OrderStatus::Delivered;
    }

    private  function prepareData() : \JsonSerializable {

        $order = [
            "Orders" => [
                "deliveryDate" => ($this->order->delivery_date)->format('m-d-Y'),
                "Address" => $this->order->shipping_address,
                "customer" => $this->order->customer_name
            ]
        ];

        foreach ($this->order->items as $item) {
            $order['Orders']['Items'][] = [
                "itemid" => $item->partner_item_id,
                "orderqty" => $item->item_quantity
            ];
        }

        return json_encode($order, JSON_THROW_ON_ERROR);
    }
}
