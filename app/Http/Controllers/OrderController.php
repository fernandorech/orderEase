<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeOrderRequest;
use App\Interfaces\OrderRepositoryInterface;
use App\Jobs\ProcessOrder;
use App\Models\Order;
use App\Jobs\SendOrder;


class OrderController extends Controller
{
    public function __construct(
        public OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function insert(storeOrderRequest $request)
    {

        $data = $request->toArray();

        $order  = $this->orderRepository->insert($data);

        ProcessOrder::dispatch($order);

        return response()->json(
            [
            'success' => true,
            'data' => [
                'order' => $order->id,
                'status' => $order->status
            ]
            ]
        );
    }
}
