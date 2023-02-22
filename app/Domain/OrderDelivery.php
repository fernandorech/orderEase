<?php

namespace App\Domain;

use App\Domain\Contracts\PartnerInterface;
use App\Domain\Types\OrderStatus;
use Exception;

class OrderDelivery
{
    private static array $requests = [
        1 => Requests\Api::class,
        2 => Requests\CSV::class
    ];

    public function __construct(
        public PartnerInterface $order,
    ) {
    }


    public static function from($order) : self
    {
        if(!array_key_exists($order->partner_id, self::$requests)) {
            throw new Exception('Partner Id is not avaliable');
        }

        $partner =  new self::$requests[$order->partner_id]($order);
        return new OrderDelivery($partner);
    }

    public function run() : OrderStatus
    {
        return $this->order->run();
    }

    public function returnInstance() : object
    {
        return $this->order;
    }

    public function isInstanceOf($class) : bool
    {
        return ((new \ReflectionClass($this->order))->getShortName() == $class) ? true : false ;
    }
}
