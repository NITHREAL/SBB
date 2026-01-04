<?php

declare(strict_types=1);

namespace Domain\Exchange\Requests;

use Illuminate\Validation\Rule;

class ProductItemRequest extends ItemRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id1c = $this->get('system_id');

        return [
            'system_id' => ['required', Rule::unique('products')->ignore($id1c, 'system_id')],
            'unit_system_id' => 'required|string',
            'categories_1c_id' => 'array|nullable',
            'categories_1c_id.*' => 'required|string|exists:categories,system_id',
            'properties_1c_id' => 'array|nullable',
            'properties_1c_id.*' => 'string|nullable',
            'active' => 'required|boolean',
            'sku' => ['string', 'nullable', Rule::unique('products')->ignore($id1c, 'system_id')],
            'title' => 'required|string',
            'description' => 'string|nullable',
            'composition' => 'string|nullable',
            'storage_conditions' => 'string|nullable',
            'proteins' => 'numeric|min:0|nullable',
            'fats' => 'numeric|min:0|nullable',
            'carbohydrates' => 'numeric|min:0|nullable',
            'nutrition_kcal' => 'numeric|min:0|nullable',
            'nutrition_kj' => 'numeric|min:0|nullable',
            'is_weight' => 'boolean|nullable',
            'weight' => 'numeric|min:0|nullable',
            'shelf_life' => 'integer|min:0|nullable',
            'delivery_in_country' => 'boolean|nullable',
            'by_preorder' => 'boolean|nullable',
            'delivery_dates' => 'array|nullable',
            'delivery_dates.*' => 'date_format:Y-m-d',
            'cooking' => 'boolean|nullable',
            'is_ready_to_eat' => 'boolean|nullable',
            'barcodes' => 'array|nullable',
            'barcodes.*' => 'string',
        ];
    }
}

