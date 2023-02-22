<?php

namespace App\Domain;

use App\Domain\Contracts\PartnerInterface;
use App\Domain\Types\OrderStatus;
use Exception;

class OrderDelivery
{
    private object $receiver;

    private static array $partners = [
        1 => Requests\Api::class,
        2 => Requests\CSV::class
    ];

    private function __construct(PartnerInterface $partner )
    {
        $this->receiver = $partner;
    }

    public static function from($order) : self
    {
        if(!array_key_exists($order->partner_id, self::$partners)) {
            throw new Exception('Partner Id is not avaliable');
        }

        $partner =  new self::$partners[$order->partner_id]($order);
        return new OrderDelivery($partner);
    }

    public function run() : OrderStatus
    {
        return $this->receiver->run();
    }

    public function returnInstance() : object
    {
        return $this->receiver;
    }

    public function isInstanceOf($class) : bool
    {
        return ((new \ReflectionClass($this->receiver))->getShortName() == $class) ? true : false ;
    }

}
