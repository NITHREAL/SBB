<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\Order\Models\Payment\PaymentType;

class OrderPaymentTypeFilter extends RelationFilter
{
    public $parameters = [
        'payment_type'
    ];

    protected string $modelClassName = PaymentType::class;

    protected string $modelColumnName = 'title';

    protected string $modelColumnKey = 'code';

    public function name(): string
    {
        return __('admin.order.payment_type');
    }
}
