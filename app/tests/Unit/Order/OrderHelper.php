<?php

namespace Tests\Unit\Order;

use Domain\Product\Models\Product;
use Illuminate\Support\Str;
use Infrastructure\Services\Buyer\Facades\BuyerStore;
use Domain\Order\Helpers\OrderStatusHelper;
use Domain\Promocode\Models\Promocode;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;
use Pest\Expectation;

class OrderHelper {
    const DELIVERY_TYPE_PICKUP = 'pickup';

    const DELIVERY_TYPE_DELIVERY = 'delivery';

    const DELIVERY_SUB_TYPE = "today";

    const DELIVERY_TIME = '12_13';

    const DELIVERY_TIME_LABEL = "10:00 - 21:00";

    const PAYMENT_IN_STORE = "by_store";

    const PAYMENT_ONLINE = "by_online";

    public static array $orderReview = [
        'id',
        'orderId',
        'rating',
        'text',
    ];

    public static array $orderCreateStructure = [
        "id",
        "systemId",
        "status",
        "bindingId",
        "canCancel",
        "paymentType",
        "deliveryType",
        "deliverySubType",
        "receiveDate",
        "receiveInterval",
        "productsCount",
        "productsTotal",
        "products" => [
            [
                "id",
                "title",
                "slug",
                "image",
                "imageBlurHash",
                "sort",
            ]
        ],
        "needPayment",
        "promo",
        "address",
        "sberUrl",
    ];

    public static array $orderStructure = [
            "id",
            "systemId",
            "status",
            "paymentType",
            "paymentTypeLabel",
            "deliveryType",
            "deliverySubType",
            "date",
            "time",
            "productsCount",
            "productsTotal",
            "total",
            "deliveryCost",
            "discount",
            "reviewAvailable",
            "electronicCheck",
            "rate",
            "needPayment",
            "sberUrl",
            "canCancel",
            "address",
            "products" => [
                [
                    "id",
                    "image",
                    "imageBlurHash",
                    "title",
                    "slug",
                    "rating",
                    "unit",
                    "weight",
                    "count",
                    "price",
                    "priceDiscount",
                    "total",
                    "favorited",
                    "sumUnit",
                ]
            ],
            "comment",
    ];

    public static array $orderRepeatStructure = [
        'token',
        'promocode',
        'coupon',
        'couponTitle',
        'total',
        'totalWithoutDiscount',
        'productsTotal',
        'productsTotalWithoutDiscount',
        'discount',
        'delivery',
        'bonuses',
        'baskets',
        'proposedProducts',
    ];

    public static function getDeliveryDate(int $count = 1): string
    {
        return Carbon::now()->addDays($count)->format('Y-m-d');
    }

    public static function getCreateExpect(TestResponse $response)
    {
        $order = Arr::get($response, '0');

        $product = Arr::first(Arr::get($order, 'products'));

        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure([self::$orderCreateStructure])
            ->and(Arr::get($order, 'id'))->toBeInt()
            ->and(Arr::get($order, 'systemId'))->toBeString()
            ->and(Arr::get($order, 'status'))->toBeString()
            ->and(Arr::get($order, 'bindingId'))->toBeNull()
            ->and(Arr::get($order, 'canCancel'))->toBeBool()
            ->and(Arr::get($order, 'paymentType'))->toBeString()
            ->and(Arr::get($order, 'deliveryType'))->toBeString()
            ->and(Arr::get($order, 'deliverySubType'))->toBeString()
            ->and(Arr::get($order, 'receiveDate'))->toBeString()
            ->and(Arr::get($order, 'receiveInterval'))->toBeString()
            ->and(Arr::get($order, 'productsCount'))->toBeInt()
            ->and(Arr::get($order, 'productsTotal'))->toBeFloat()
            ->and(Arr::get($order, 'products'))->toBeArray()
            ->and(Arr::get($product, 'id'))->toBeInt()
            ->and(Arr::get($product, 'title'))->toBeString()
            ->and(Arr::get($product, 'slug'))->toBeString()
            ->and(Arr::get($product, 'image'))->toBeNull()
            ->and(Arr::get($product, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($product, 'sort'))->toBeInt()
            ->and(Arr::get($order, 'needPayment'))->toBeBool()
            ->and(Arr::get($order, 'promo'))->toBeNull()
            ->and(Arr::get($order, 'address'))->toBeString()
            ->and(Arr::get($order, 'sberUrl'))->toBeNull();
    }

    public static function getOrderRepeatExpect(
        Product $product,
        TestResponse $response,
        int $count,
        Promocode $promocode = null,
    ): Expectation {
        $basket = Arr::first(
            Arr::get($response, 'baskets')
        );
        $products = Arr::first(
            Arr::get($basket, 'products')
        );

        $price = $product->price * $count;

        if ($promocode) {
            if ($promocode->percentage) {

                $priceDiscount = round(($price * ((100 - $promocode->discount) / 100)), 2);
            } else {
                $priceDiscount = $price - $promocode->discount;
            }
            $discount = round(($price - $priceDiscount), 2);
        } else {
            $priceDiscount = $price;
            $discount = 0;
        }

        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$orderRepeatStructure)
            ->and(Arr::get($response, 'token'))->toBeString()
            ->and(Arr::get($response, 'baskets'))->toBeArray()
            ->and(Arr::get($response, 'proposedProducts'))->toBeArray()
            ->and(Arr::get($response, 'total'))->toEqual(round($priceDiscount))
            ->and(Arr::get($response, 'productsTotalWithoutDiscount'))->toEqual(round($price))
            ->and(Arr::get($response, 'discount'))->toEqual($discount)
            ->and(Arr::get($response, 'delivery'))->toEqual(0)
            ->and(Arr::get($response, 'bonuses'))->toBeNull()
            ->and($basket)->toHaveKey('products')
            ->and(Arr::get($products, 'id'))->toEqual($product->id);
    }

    public static function getOrderExpect(TestResponse $response)
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$orderStructure);
    }

    public static function getOrdersExpect(TestResponse $response)
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure([
                'orders' => [self::$orderStructure]
            ]);
    }

    public static function getOrdersFinishedStatusesExpect(TestResponse $response): void
    {
        $response = json_decode($response->content(), true);

        $statuses = Arr::pluck(Arr::get($response, 'orders'), 'status');

        foreach ($statuses as $status) {
            expect(OrderStatusHelper::getFinishedStatuses())->toContain($status);
        }
    }

    public static function getOrdersPendingStatusesExpect(TestResponse $response): void
    {
        $response = json_decode($response->content(), true);

        $statuses = Arr::pluck(Arr::get($response, 'orders'), 'status');

        foreach ($statuses as $status) {
            expect(OrderStatusHelper::getPendingStatuses())->toContain($status);
        }
    }

    public static function getOrderPriceExpect($response, $order): void
    {
        $response = json_decode($response->content(), true);

        $responseOrder = Arr::first(Arr::get($response, 'orders'));

        expect(Arr::get($responseOrder, 'total'))->toEqual(round($order->total_price));
    }
    public static function getOrderReviewExpect(TestResponse $response)
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$orderReview);
    }




    public static function getOrderFieldsExpect(array|TestResponse $data): Expectation
    {
//        dump($data);
        $product = Arr::first(Arr::get($data, 'products'));

        return expect(Arr::get($data, 'id'))->toBeInt()
            ->and(Arr::get($data, 'systemId'))->toBeString()
            ->and(Arr::get($data, 'status'))->toBeString()
            ->and(Arr::get($data, 'paymentType'))->toBeString()
            ->and(Arr::get($data, 'paymentTypeLabel'))->toBeString()
            ->and(Arr::get($data, 'deliveryType'))->toBeString()
            ->and(Arr::get($data, 'deliverySubType'))->toBeString()
            ->and(Arr::get($data, 'date'))->toBeString()
            ->and(Arr::get($data, 'time'))->toBeString()
            ->and(Arr::get($data, 'productsCount'))->toBeInt()
            ->and(Arr::get($data, 'productsTotal'))->toBeInt()
            ->and(Arr::get($data, 'total'))->toBeInt()
            ->and(Arr::get($data, 'deliveryCost'))->toEqual(0)
            ->and(Arr::get($data, 'discount'))->toBeNull()
            ->and(Arr::get($data, 'reviewAvailable'))->toBeBool()
            ->and(Arr::get($data, 'electronicCheck'))->toBeBool()
            ->and(Arr::get($data, 'rate'))->toBeNull()
            ->and(Arr::get($data, 'needPayment'))->toBeBool()
            ->and(Arr::get($data, 'canCancel'))->toBeBool()
            ->and(Arr::get($data, 'address'))->toBeString()
            ->and(Arr::get($data, 'products'))->toBeArray()
            ->and(Arr::get($product, 'id'))->toBeInt()
            ->and(Arr::get($product, 'image'))->toBeNull()
            ->and(Arr::get($product, 'imageBlurHash'))->toBeNull()
            ->and(Arr::get($product, 'title'))->toBeString()
            ->and(Arr::get($product, 'slug'))->toBeString()
            ->and(Arr::get($product, 'rating'))->toEqual(0)
            ->and(Arr::get($product, 'unit'))->toBeString()
            ->and(Arr::get($product, 'weight'))->toEqual(0)
            ->and(Arr::get($product, 'count'))->toBeInt()
            ->and(Arr::get($product, 'price'))->toBeInt()
            ->and(Arr::get($product, 'priceDiscount'))->toBeNull()
            ->and(Arr::get($product, 'total'))->toBeInt()
            ->and(Arr::get($product, 'favorited'))->toBeBool()
            ->and(Arr::get($product, 'sumUnit'))->toBeString()
            ->and(Arr::get($data, 'comment'))->toBeString()
            ->and(Arr::get($data, 'bindingId'))->toBeNull();
    }

    public static function getMockBasketData($store, $product, $buyerToken = null): array
    {
        return [
            'token'                         => $buyerToken ?? Str::uuid(),
            'promocode'                     => null,
            'coupon'                        => null,
            'couponTitle'                   => null,
            'total'                         => $product->price,
            'totalWithoutDiscount'          => $product->price,
            'productsTotal'                 => $product->price,
            'productsTotalWithoutDiscount'  => $product->price,
            'discount'                      => 0.0,
            'delivery'                      => 0,
            'bonuses'                       => 6.0,
            'proposedProducts'              => [],
            'baskets'                       =>
                [
                    [
                        'date'                          => OrderHelper::getDeliveryDate(),
                        'time'                          => OrderHelper::DELIVERY_TIME,
                        'deliveryType'                  => OrderHelper::DELIVERY_TYPE_PICKUP,
                        'deliverySubType'               => OrderHelper::DELIVERY_SUB_TYPE,
                        'deliveryPrice'                 => 0,
                        'deliveryAddress'               => $store->address,
                        'storeOneCId'                   => $store->getAttribute('system_id'),
                        'storeId'                       => $store->id,
                        'cityId'                        => $store->city_id,
                        'total'                         => $product->price,
                        'totalWithoutDiscount'          => $product->price,
                        'productsTotal'                 => $product->price,
                        'productsTotalWithoutDiscount'  => $product->price,
                        'discount'                      => 0.0,
                        'products'                      => collect(
                                                                [OrderHelper::getPreparedProduct($product)],
                                                            ),
                        'unavailableProducts'           => [],
                        'isAvailable'                   => true,
                        'availableFrom'                 => 0,
                        'timeLabel'                     => self::DELIVERY_TIME_LABEL,
                    ]
                ],
        ];
    }

    public static function getCreateOrderRequest($store): array
    {
        return [
            'bindingId'         =>  null,
            'comment'           => 'comment',
            'coupon'            => null,
            'electronicChecks'  => false,
            'paymentType'       => self::PAYMENT_IN_STORE,
            'promo'             => null,
            'source'            => "site",
            'delivery'          =>
                [
                    [
                        'deliveryType'      => OrderHelper::DELIVERY_TYPE_PICKUP,
                        'deliverySubType'   => OrderHelper::DELIVERY_SUB_TYPE,
                        'deliveryDate'      => OrderHelper::getDeliveryDate(),
                        'address'           => $store->address,
                        'cityId'            => $store->city_id,
                        'deliveryTime'      => OrderHelper::DELIVERY_TIME,
                        'storeOneCId'       => $store->getAttribute('system_id'),
                    ]
                ],
        ];
    }

    public static function getInvalidTypesOrderRequest(): array
    {
        return [
            'bindingId'         => [],
            'comment'           => 2,
            'coupon'            => [],
            'electronicChecks'  => 2,
            'paymentType'       => 2,
            'promo'             => [],
            'source'            => 2,
            'delivery'          =>
                [
                    [
                        'deliveryType'      => 2,
                        'deliverySubType'   => 2,
                        'deliveryDate'      => 2,
                        'address'           => 2,
                        'cityId'            => 'cityId',
                        'deliveryTime'      => 2,
                        'storeOneCId'       => 2,
                    ]
                ],

        ];
    }

    public static function getMockBuyerStore(): void
    {
        BuyerStore::shouldReceive('getSelectedStore')
            ->andReturn(OrderModelHelper::$store);
        BuyerStore::shouldReceive('getTitle')
            ->once()
            ->andReturn(OrderModelHelper::$store->title);
        BuyerStore::shouldReceive('getValue')
            ->once()
            ->andReturn(
                [
                    'id'        => OrderModelHelper::$store->getAttribute('id'),
                    '1c_id'     => OrderModelHelper::$store->getAttribute('1c_id'),
                    'title'     => OrderModelHelper::$store->getAttribute('title'),
                    'city_id'   => OrderModelHelper::$store->getAttribute('city_id'),
                    'address'   => OrderModelHelper::$store->getAttribute('address'),
                    'latitude'  => OrderModelHelper::$store->getAttribute('latitude'),
                    'longitude' => OrderModelHelper::$store->getAttribute('longitude'),
                ]
            );
        BuyerStore::shouldReceive('getId')
            ->once()
            ->andReturn(OrderModelHelper::$store->getAttribute('id'));
    }

    public static function getCanNotCancelOrderError(): array
    {
        return [
            "message" => "Нельзя отменить заказ в текущем состоянии"
        ];
    }

    public static function getOrderWrongUserError(): array
    {
        return [
            "message" => "Пользовтель не имеет права отменять данный заказ"
        ];
    }

    public static function getPreparedProduct($product): Product
    {
        $product->setAttribute('id1C', $product->getAttribute('system_id'));
        $product->setAttribute('unit_system_id', $product->unit_system_id);
        $product->setAttribute('price', $product->price);
        $product->setAttribute('price_discount', null);
        $product->setAttribute('count', 1);
        $product->setAttribute('sum', $product->price);
        $product->setAttribute('sum_prev', $product->price);
        $product->setAttribute('priceUnit', "1 шт");
        $product->setAttribute('sumUnit', "1 шт");
        $product->setAttribute('prices',
            ['original' =>
                [
                    "price" => $product->price,
                    "price_discount" => null,
                ],
                "display" =>
                    [
                        "price" => $product->price,
                        "price_discount" => null,
                    ],
                'real' =>
                    [
                        "price" => $product->price,
                        "price_discount" => null,
                    ]
            ]);

        return $product;
    }
}
