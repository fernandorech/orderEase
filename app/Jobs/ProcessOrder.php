<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Domain\OrderDelivery;


class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order,
    ) {
    }

    public function handle(): void
    {

        $order = OrderDelivery::from($this->order);
        $response = $order->run();

        $this->order->status = $response->value;
        $this->order->save();

    }


}
