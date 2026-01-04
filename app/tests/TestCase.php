<?php

namespace Tests;

use Carbon\Carbon;
use Domain\Basket\Models\Basket;
use Domain\City\Models\City;
use Domain\City\Models\Region;
use Domain\Farmer\Models\Farmer;
use Domain\Order\Models\Delivery\Polygon;
use Domain\Order\Models\Delivery\PolygonType;
use Domain\Order\Models\Order;
use Domain\Product\Models\Category;
use Domain\Product\Models\Product;
use Domain\Product\Models\Review;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\Promocode\Models\Promocode;
use Domain\Store\Models\ProductStore;
use Domain\Store\Models\Store;
use Domain\Store\Models\StoreScheduleWeekday;
use Domain\Unit\Models\Unit;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Infrastructure\Services\Auth\Token;
use Tests\Unit\Order\OrderHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    function createUser(): User
    {
        return User::factory()->create();
    }

    function createUserToken(User $user = null): array
    {
        $user = $user ?? $this->createUser();

        return Token::create($user);
    }

    function createCity(): City
    {
        return City::factory()->create([
            'region_id' => Region::factory()->create()->id
        ]);
    }

    function createStore(City $city): Store
    {
        return Store::factory()->create([
            'active' => true,
            'is_dark_store' => true,
            'city_id' => $city->id,
        ]);
    }

    function createPolygon(Store $store, array $coordinates): Polygon
    {
        return Polygon::factory()->create([
            'store_system_id'   => $store->id,
            'coordinates'      => $coordinates,
        ]);
    }

    function createPolygonType(string $deliveryType): PolygonType
    {
        return PolygonType::factory()->create([
            'delivery_type' => $deliveryType,
        ]);
    }

    function createStoreScheduleWeekday(
        Store $store,
        PolygonType $polygonType,
    ): StoreScheduleWeekday {
        return StoreScheduleWeekday::factory()->create([
            'store_id' => $store->id,
            'polygon_type_id' => $polygonType->id,
            'week_day' => Str::lower(Carbon::tomorrow()->englishDayOfWeek),
        ]);
    }

    public static function createProduct(
        string $unit,
        bool $by_preorder,
        string $farmerSystemId = null,
    ): object {
        $data = [
            'weight' => false,
            'by_preorder' => $by_preorder,
            'show_as_preorder' => $by_preorder,
            'unit_system_id' => $unit,
            'cooking'   => false,
            'vegan' => false,
        ];
        if ($farmerSystemId) {
            $data['farmer_system_id'] = $farmerSystemId;
        }
        return Product::factory()->create($data);
    }

    public function addLeftovers(
        Product $product,
        Store $store,
        int $count,
    ): object {
        $productSystemId = $product->getAttribute('system_id');
        $storeSystemId = $store->getAttribute('system_id');

        $price = round((rand(500, 5000) / 10), 2) + 0.01;

        $leftover = [
            'product_system_id' => $productSystemId,
            'store_system_id' => $storeSystemId,
            'active' => true,
            'price' => $price,
            'price_discount' => 0.0,
            'discount_expires_in' => null,
            'count' => $count,
            'delivery_schedule' => json_encode(["wednesday", "thursday", "friday", "saturday", "sunday", "monday", "tuesday"])
        ];

        $leftover['hash'] = ProductStore::makeHash($productSystemId, $storeSystemId);

        DB::table('product_store')->insert($leftover);

        return Product::query()
            ->baseQuery()
            ->addSelect('products.unit_system_id')
            ->where('products.system_id', $product->getAttribute('system_id'))
            ->where('leftovers.store_system_id', $store->getAttribute('system_id'))
            ->first();
    }

    function createUnit(string $title): Unit
    {
        return Unit::factory()->create([
            'title' => $title,
        ]);
    }

    public static function createBasket(
        User $user,
        Store $store,
        array $delivery,
    ): Basket
    {
        $basket = Basket::factory()->create([
            'user_id' => $user->id,
            'delivery_params' =>
                [[
                    "cityId" => $store->getAttribute('city_id'),
                    "address" => $store->address,
                    "storeId" => $store->id,
                    "latitude" => "55.39099200000000",
                    "cityTitle" => "Кемерово",
                    "longitude" => "86.04682700000000",
                    "store1cId" => $store->getAttribute('system_id'),
                    "deliveryType" => Arr::get($delivery, 'delivery_type'),
                    "deliverySubType" => Arr::get($delivery, 'delivery_sub_type'),
                    "deliveryIntervalDate" => Arr::get($delivery, 'delivery_date'),
                    "deliveryIntervalTime" => Arr::get($delivery, 'delivery_time'),
                    "deliveryPolygonTypes" => [],
                ]],
        ]);

        return $basket;
    }

    public function associateBasket(
        Basket $basket,
        Product $product,
        int $count,
    ): void {
        $basket->products()->attach(
            $product,
            ['count' => $count, 'from_order' => 0]
        );
    }
    public static function createPromocode(
        bool $percentage,
        float $discount,
    ): Promocode {
        return Promocode::factory()->create(
            [
                'code' => Str::random(6),
                'free_delivery' => false,
                'order_type' => 'any',
                'delivery_type' => 'any',
                'any_user' => true,
                'one_use_per_phone' => true,
                'expires_in' => null,
                'discount' => $discount,
                'min_amount' => 50.0,
                'limit' => null,
                'percentage' => $percentage,
                'any_product' => true,
                'mobile' => false,
                'active' => true,
                'only_one_use' =>  false,
                'use_excluded' => false,
            ]
        );
    }

    public static function setBuyerToken(): string
    {
        return Str::uuid();
    }

    public static function createMockOrder(
        string $payment_type,
        string $status,
        Store $store,
        User $user,
        string $deliveryType = OrderHelper::DELIVERY_TYPE_PICKUP,
    ): Order {
        return Order::factory()->create([
            'store_system_id' => $store->getAttribute('system_id'),
            'payment_type' => $payment_type,
            'delivery_type' => $deliveryType,
            'delivery_sub_type' => OrderHelper::DELIVERY_SUB_TYPE,
            'status' => $status,
            'receive_date' => Carbon::tomorrow()->format('Y-m-d'),
            'receive_interval' => OrderHelper::DELIVERY_TIME,
            'request_from' => 'site',
            'user_id' => $user->id,
        ]);
    }

    public static function associateOrder(
        Order $order,
        Product $product,
    ): Order {
        $order->products()->sync(

            [$product['id1C'] => [
                'unit_system_id'            => $product->unit_system_id,
                'price'                     => (float) $product->price,
                'price_discount'            => null,
                'price_promo'               => null,
                'price_buy'                 => (float) $product->price,
                'count'                     => 1,
                'weight'                    => 0,
                'is_discount'               => false,
                'total'                     => (float) $product->price,
                'total_without_discount'    => (float) $product->price,
            ]]
        );

        $order->update([
            'total_price' => $product->price,
        ]);

        return $order;
    }

    function createProductReview($user, $product): Review
    {
        return Review::factory()->create([
            'product_id'    => $product->id,
            'user_id'       => $user->id,
            'user_name'     => $user->firstName,
            'user_phone'    => $user->phone,
            'active'        => true,
        ]);
    }

    public static function createFarmer(): Farmer {
        return Farmer::factory()->create([
            'active'    => true,
        ]);
    }

    public static function createProductGroup(): ProductGroup {
        return ProductGroup::factory()->create([
            'active'    => true,
        ]);
    }

    public function associateProductGroup(
        ProductGroup $group,
        Product $product,
    ): void {
        $group->products()->attach(
            $product
        );
    }

    public static function createCategories(): void
    {
        DB::table('categories')->insert([
            'system_id'         => Str::uuid(),
            'parent_system_id'  => null,
            'active'            => true,
            'margin_left'       => rand(400, 600),
            'margin_right'      => rand(400, 600),
            'level'             => 0,
            'title'             => Str::random(10),
            'sort'              => rand(400, 600),
            'slug'              => Str::random(10),
            'special_type'      => false,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        $category = Category::query()->latest()->first();

        DB::table('categories')->insert([
            'system_id'         => Str::uuid(),
            'parent_system_id'  => $category->system_id,
            'active'            => true,
            'margin_left'       => rand(400, 600),
            'margin_right'      => rand(400, 600),
            'level'             => 0,
            'title'             => Str::random(10),
            'sort'              => rand(400, 600),
            'slug'              => Str::random(10),
            'special_type'      => false,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }

    public function associateCategory(
        object $category,
        object $product,
    ): void {
        $category->products()->attach(
            $product,
            [
                'product_system_id' => $product->id1C,
            ],
        );

    }

    public function addRelatedProduct(
        object $product,
        object $relatedProduct,
    ): void {
        $relatedProductsData[$relatedProduct->id] = [
            'related_product_id'    => $relatedProduct->id,
            'sort'                  => 500,
        ];

        $product->relatedProducts()->sync($relatedProductsData);
    }
}
