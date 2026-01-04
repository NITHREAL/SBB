<?php

namespace Domain\Product\Services\Catalog\Filters\List\Types;

use Domain\Farmer\Models\Farmer;
use Domain\Product\Services\Catalog\Filters\List\BaseListFilter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FarmersFilter extends BaseListFilter
{
    protected string $title = 'Фермеры';

    protected string $slug = 'farmers';

    public function __construct(
        array $selectedItems,
        Collection $products,
    ) {
        parent::__construct();

        $this->setValues($selectedItems, $products);
    }

    private function setValues(array $selectedItems, Collection $products): void
    {
        $result = [];

        $farmers = Farmer::query()
            ->active()
            ->orderBy('name')
            ->get();

        foreach ($farmers as $farmer) {
            $isAvailable = $products->where('farmerId', $farmer->id)->count() > 0;

            $result[] = $this->getPreparedFarmerFilterValue($farmer, $selectedItems, $isAvailable);
        }

        $this->values = Arr::sort($result, fn($item) => !$item['isAvailable']);
    }

    private function getPreparedFarmerFilterValue(
        Farmer $farmer,
        array $selectedItems,
        bool $isAvailable,
    ): array {
        return [
            'title'         => $farmer->name,
            'value'         => $farmer->id,
            'isAvailable'   => $isAvailable,
            'isSelected'    => in_array($farmer->id, $selectedItems),
        ];
    }
}
