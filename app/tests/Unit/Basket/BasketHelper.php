<?php

namespace Tests\Unit\Basket;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;
use Pest\Expectation;

class BasketHelper
{
    public static array $coordinates = [
        [55.427995684756155, 86.0852958478947],
        [55.43136340170766, 86.09980123424724],
        [55.43082653862545, 86.10187189960669],
        [55.430545902726685, 86.10349195385169],
        [55.430362878244814, 86.10416787052347],
        [55.42983515286189, 86.10504227066232],
        [55.42920980437693, 86.10570209407999],
        [55.42859969869572, 86.10623317146491],
        [55.427147609110285, 86.10513346576883],
        [55.42665247811336, 86.10478446077482],
        [55.42625847533096, 86.10468506013021],
        [55.42552453696863, 86.10479629234877],
        [55.424837225332375, 86.1047982642777],
        [55.423154790857026, 86.1039533038209],
        [55.42288797167899, 86.10394519257677],
        [55.42177623190699, 86.10519720314086],
        [55.418442552857705, 86.12406354385566],
        [55.41173686610135, 86.12769681151087],
        [55.397529525721644, 86.11425978163797],
        [55.39851822558009, 86.11096329244198],
        [55.398499796361975, 86.11049871885128],
        [55.39800511362274, 86.10705152883608],
        [55.395800394181094, 86.10800442020718],
        [55.39559844388915, 86.10797025866083],
        [55.39443191499384, 86.10692530811038],
        [55.39338748308094, 86.10585889988776],
        [55.3921851228553, 86.10475718302865],
        [55.39108654376534, 86.10364473733343],
        [55.38916499436927, 86.10188208126252],
        [55.38775769053271, 86.10271108034274],
        [55.38699287302722, 86.09914763347652],
        [55.38673231107467, 86.09779477072419],
        [55.38586097863209, 86.0895754528938],
        [55.38444306791752, 86.08850529337813],
        [55.382707925115135, 86.08758397552215],
        [55.38133943643704, 86.08733789331534],
        [55.380239791176656, 86.08738651358888],
        [55.37931121628733, 86.08726062002079],
        [55.37795499453266, 86.08561123173233],
        [55.37697429412977, 86.0844072769033],
        [55.37659390003046, 86.08309719630881],
        [55.37593247661089, 86.08210898079622],
        [55.37390699995145, 86.08052810373636],
        [55.371195676472766, 86.07767169802348],
        [55.37400754448479, 86.07311035943394],
        [55.37524539559728, 86.06836566180434],
        [55.376018906665024, 86.06312743771599],
        [55.37759099436358, 86.0533743946937],
        [55.37926075958716, 86.04561691517846],
        [55.38130410938136, 86.04642675226128],
        [55.382638804295034, 86.04564872160728],
        [55.385353844083795, 86.04677346701853],
        [55.387114393531824, 86.04698233757406],
        [55.39198028738408, 86.05253334968947],
        [55.39899272579643, 86.05810179575991],
        [55.399999600387666, 86.05962177205191],
        [55.400871156834135, 86.06135614873573],
        [55.40231048013189, 86.06545399387159],
        [55.4103231605862, 86.08320135606375],
        [55.414746309165366, 86.0759485211297],
        [55.419807799039326, 86.07820371761464],
        [55.427995684756155, 86.0852958478947]
    ];

    public static array $basketStructure = [
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

    public static function getMainExpect(TestResponse $response): Expectation
    {
        return expect($response)
            ->assertStatus(200)
            ->assertJsonStructure(self::$basketStructure)
            ->and(Arr::get($response, 'token'))->toBeString()
            ->and(Arr::get($response, 'baskets'))->toBeArray()
            ->and(Arr::get($response, 'proposedProducts'))->toBeArray()
            ;
    }

    public static function getEmptyBasketExpect(TestResponse $response): Expectation
    {
        return expect(Arr::get($response, 'promocode'))->toBeNull()
            ->and(Arr::get($response, 'coupon'))->toBeNull()
            ->and(Arr::get($response, 'couponTitle'))->toBeNull()
            ->and(Arr::get($response, 'total'))->toEqual(0)
            ->and(Arr::get($response, 'totalWithoutDiscount'))->toEqual(0)
            ->and(Arr::get($response, 'productsTotal'))->toEqual(0)
            ->and(Arr::get($response, 'productsTotalWithoutDiscount'))->toEqual(0)
            ->and(Arr::get($response, 'discount'))->toEqual(0)
            ->and(Arr::get($response, 'delivery'))->toEqual(0)
            ->and(Arr::get($response, 'bonuses'))->toEqual(0);
    }

    public static function getExpectWithProducts(
        TestResponse $response,
        object $product,
        int $count,
        object $promocode = null,
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

        return expect(Arr::get($response, 'total'))->toEqual((int) round($priceDiscount))
            ->and(Arr::get($response, 'productsTotalWithoutDiscount'))->toEqual((int) round($price))
            ->and(Arr::get($response, 'discount'))->toEqual((int) round($discount))
            ->and(Arr::get($response, 'delivery'))->toEqual(0)
            ->and(Arr::get($response, 'bonuses'))->toBeNull()
            ->and($basket)->toHaveKey('products')
            ->and(Arr::get($products, 'id'))->toEqual($product->id);
    }

    public static function getUser(): User
    {
        return BasketModelHelper::$user;
    }

    public static function getNotExistsProductError(): array
    {
        return [
            "message" => "Ошибка при добавлении товара в корзину. Товар отсутствует или недоступен"
        ];
    }

    public static function getNotExistsInBasketProductError(): array
    {
        return [
            "message" => "Товара с таким идентификатором в корзине нет"
        ];
    }

    public static function getNotEnoughProductError(): array
    {
        return [
            "message" => "Выбрано максимальное количество товара. Приобрести больше можно выбрав заказ на другой день"
        ];
    }
}
