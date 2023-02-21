<?php

namespace Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;
use App\Domain\OrderDelivery;
use App\Domain\Types\OrderStatus;
use App\Domain\Contracts\PartnerInterface;
use App\Domain\Partners\Api;
use App\Domain\Partners\CSV;

class OrderDeliveryTest extends TestCase
{
    public function testCanCreateOrderDeliveryInstanceFromApiPartner()
    {
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getAttribute'])
            ->getMockForAbstractClass();

        $order->method('getAttribute')
            ->with('partner_id')
            ->willReturn(1);

        $delivery = OrderDelivery::from($order);

        $this->assertInstanceOf(OrderDelivery::class, $delivery);
        $this->assertInstanceOf(Api::class, $delivery->returnInstance());
        $this->assertTrue($delivery->isInstanceOf('Api'));
    }

    public function testCanCreateOrderDeliveryInstanceFromCSVPartner()
    {
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getAttribute'])
            ->getMockForAbstractClass();

        $order->method('getAttribute')
            ->with('partner_id')
            ->willReturn(2);

        $delivery = OrderDelivery::from($order);

        $this->assertInstanceOf(OrderDelivery::class, $delivery);
        $this->assertInstanceOf(CSV::class, $delivery->returnInstance());
        $this->assertTrue($delivery->isInstanceOf('CSV'));
    }

    public function testThrowsExceptionWhenCreatingOrderDeliveryInstanceWithUnknownPartner()
    {
        $order = $this->getMockBuilder(Order::class)
            ->onlyMethods(['getAttribute'])
            ->getMockForAbstractClass();

        $order->method('getAttribute')
            ->with('partner_id')
            ->willReturn(99);

        $this->expectException(\Exception::class);
        OrderDelivery::from($order);
    }
    /*
    public function testCanRunOrderDeliveryAndReceiveOrderStatus()
    {
        $partnerMock = $this->createMock(PartnerInterface::class);
        $partnerMock->method('run')->willReturn(OrderStatus::SHIPPED);

        $delivery = new OrderDelivery($partnerMock);
        $orderStatus = $delivery->run();

        $this->assertEquals(OrderStatus::SHIPPED, $orderStatus);
    } */
}
