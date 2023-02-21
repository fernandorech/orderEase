<?php

namespace App\Domain\Partners;

use App\Domain\Contracts\PartnerInterface;
use App\Domain\Types\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class CSV implements PartnerInterface
{
    private static string $SHIPPING_REGEX = "/\s+|(?<=\d)(?=-)/";

    private static array $HEADERS = [
        'delivery date',
        'street address',
        'city',
        'state',
        'postal code',
        'customer',
        'items id',
        'items quantity'
    ];

    public function __construct(
        public Order $order,
    ) {}

    public function run() : OrderStatus
    {
        $fileName = 'order_' .$this->order->id . '.csv';

        $csv = $this->prepareData();

        Storage::disk('local')->put("Orders/" . $fileName, $csv);

        $request = Storage::createSftpDriver([
            'host' => $this->order->partner->endpoint,
            'username' => $this->order->partner->username,
            'password' => $this->order->partner->password,
        ]);
        $request->put()->put("Orders/" . $fileName, $csv);

        return OrderStatus::Delivered;
    }

    private function prepareData() {
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, self::$HEADERS);

        $products = [];
        foreach ($this->order->items as $key => $item) {
            $products[$key]['id']       = $item->partner_item_id;
            $products[$key]['quantity'] = $item->item_quantity;
        }

        $address = explode(',', $this->order->shipping_address);
        $shipping = preg_split(self::$SHIPPING_REGEX, $address[2]);

        $row = [
            "delivery date" => ($this->order->delivery_date)->format('d/m/Y'),
            "street address" => $address[0],
            "city" => $address[1],
            "state" => $shipping[1],
            "postal code" => count($shipping) > 3 ? $shipping[2] . $shipping[3] : $shipping[2],
            "customer" => $this->order->customer_name,
            "partner_id" =>  $products[0]['id'],
            "items_quantity" => $products[0]['quantity']
        ];
        fputcsv($handle, $row);

        foreach (array_slice($products, 1) as $product) {
            fputcsv($handle, ['','','','','','',$product['id'], $product['quantity']]);
        }

        rewind($handle);
        return stream_get_contents($handle);
    }
}
