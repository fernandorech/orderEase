<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Domain\Requests\Api;
use App\Domain\Types\OrderStatus;
use Illuminate\Support\Facades\Http;
class ApiTest extends TestCase
{
    public function testRunReturnsOrderStatusDeliveredOnSuccessfulRequest()
    {
        $mockResponse = Http::response('', 200);
        Http::fake([
            '*' => $mockResponse,
        ]);

        $order = new Order([
            'delivery_date' => '2023-03-01',
            'shipping_address' => '123 Main St',
            'customer_name' => 'John Doe',
        ]);

        $api = new Api($order);

        $this->assertEquals('delivered', $api->run()->value);
    }

    public function testRunReturnsOrderStatusErrorOnFailedRequest()
    {
        $mockResponse = Http::response('', 500);
        Http::fake([
            '*' => $mockResponse,
        ]);

        $order = new Order([
            'delivery_date' => '2023-03-01',
            'shipping_address' => '123 Main St',
            'customer_name' => 'John Doe',
        ]);

        $api = new Api($order);

        $this->assertEquals('error', $api->run()->value);
    }

    public function testPrepareDataReturnsJsonSerializableObject()
    {
        $order = new Order([
            'delivery_date' => '2023-03-01',
            'shipping_address' => '123 Main St',
            'customer_name' => 'John Doe',
            'items' => [
                [
                    'partner_item_id' => 'SKU123',
                    'item_quantity' => 2,
                ],
                [
                    'partner_item_id' => 'SKU456',
                    'item_quantity' => 1,
                ],
            ],
        ]);

        $api = new Api($order);

        $json = $api->prepareData();

        $this->assertInstanceOf(\JsonSerializable::class, $json);
    }
}
