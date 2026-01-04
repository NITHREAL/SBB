<?php

namespace Database\Factories;

use Carbon\Carbon;
use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'delivery_price_in_polygon_id' => null,
            'system_id' => $this->faker->uuid(),
            'uuid' => $this->faker->uuid(),
            'store_system_id' => $this->faker->uuid(),
            'user_id' => 1,
            'promo_id' => null,
            'discount' => null,
            'payment_type' => $this->faker->randomElement(
                ['by_store', 'by_online', 'by_card_on_delivery', 'by_cash_on_delivery']
            ),
            'delivery_type' => null,
            'delivery_sub_type' => null,
            'delivery_service' => null,
            'status' => 'created',
            'bill' => null,
            'comment' => 'comment',
            'delivery_cost' => 0.00,
            'receive_date' => Carbon::tomorrow()->format('Y-m-d'),
            'receive_interval' => '12_13',
            'need_exchange' => 1,
            'need_receipt' => 0,
            'amount_bonus' => 0.00,
            'completed_at' => null,
            'pay_url' => null,
            'binding_id' => null,
            'sm_original_order_id' => null,
            'sm_status' => null,
            'request_from' => $this->faker->randomElement(['site', 'mobile']),
            'total_price' => round((rand(500, 5000) / 10), 2),
            'batch' => null,
        ];
    }
}
