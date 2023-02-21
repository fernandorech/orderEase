<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use App\Domain\Types\OrderStatus;

class OrderRepository implements OrderRepositoryInterface
{
    public function insert(array $data)
    {
        $data['status'] = OrderStatus::Submitted;
        $data['ecommerce_id'] = $data['order_id'];
        $order = Order::create($data);

        $items = [];
        foreach ($data['items'] as $item) {
            $item['order_id'] = $order->id;
            $items[] = $item;
        }
        $order->items()->createMany($items);

        return $order;
    }
}
