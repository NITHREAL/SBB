<?php

namespace Domain\Promocode\Requests\Admin;

use Domain\Promocode\Enums\PromocodeDeliveryTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PromoRequest extends FormRequest
{
    public function rules(): array
    {
        $promo = $this->route('promo');
        $unique = Rule::unique('promos', 'code');
        $discount = 'required|numeric|min:0';

        if ($promo) {
            $unique = $unique->ignore($promo->id);
        }

        if (Arr::get($this->promo, 'free_delivery', false)) {
            $deliveryTypeRules = [Rule::in([PromocodeDeliveryTypeEnum::delivery()->value])];
            $discount = 'numeric|min:0|max:0';
            $anyProductRules = 'required|boolean';
        } else {
            if (Arr::get($this->promo, 'percentage')) {
                $discount .= '|max:100';
            }

            $anyProductRules = 'boolean';
            $deliveryTypeRules = ['required', Rule::in(PromocodeDeliveryTypeEnum::toValues())];
        }

        return [
            'promo.active' => 'boolean',
            'promo.mobile' => 'boolean',
            'promo.title' => 'string|nullable',
            'promo.description' => 'string|nullable',
            'promo.code' => ['required', $unique],
            'promo.discount' => $discount,
            'promo.percentage' => 'boolean',
            'promo.limit' => 'integer|nullable',
            'promo.min_amount' => 'numeric|min:0|nullable',
            'promo.order_type' => ['required'],
            'promo.delivery_type' => $deliveryTypeRules,
            'promo.any_user' => 'boolean',
            'promo.use_excluded' => 'boolean',
            'promo.any_product' => $anyProductRules,
            'promo.expires_in' => 'date_format:Y-m-d H:i:s|nullable',
            'promo.categories' => 'array',
            'promo.categories.*.id' => 'required|exists:categories,id',
            'promo.products' => 'array',
            'promo.products.*.id' => 'required|exists:products,id',
            'promo.excludedCategories' => 'array',
            'promo.excludedCategories.*.id' => 'required|exists:categories,id',
            'promo.excludedProducts' => 'array',
            'promo.excludedProducts.*.id' => 'required|exists:products,id',
            'promo.excludedGroups' => 'array',
            'promo.excludedGroups.*.id' => 'required|exists:groups,id',
            'promo.users' => 'array',
            'promo.users.*.id' => 'required|exists:users,id',
            'promo.users.*.pivot' => 'array',
            'promo.users.*.pivot.max_uses' => 'numeric|min:1|nullable',
            'promo.free_delivery' => 'boolean',
            'promo.one_use_per_phone' => 'boolean',
            'promo.only_one_use' => 'boolean',
            'promo.show_audience_id' => 'exists:audiences,id|nullable'
        ];
    }
}
