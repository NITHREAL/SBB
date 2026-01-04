<?php

namespace App\Orchid\Core\Filters;

use Illuminate\Database\Eloquent\Builder;

class HttpFilter extends \Orchid\Filters\HttpFilter
{
    /**
     * @param string|null $query
     *
     * @return string|array|null
     */
    protected function parseHttpValue($query)
    {
        if ($query === null) {
            return null;
        }

        if (is_string($query)) {
            $item = explode(',', $query);

            if (count($item) > 1) {
                return $item;
            }
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param mixed $value
     * @param string $property
     *
     * @return Builder
     */
    protected function filtersExact(Builder $query, $value, string $property): Builder
    {
        $property = self::sanitize($property);

        if (in_array($property, ['created_at', 'updated_at'])) {
            if (is_array($value)) {
                return $query
                    ->when($value['start'], fn($query) => $query->where($property, '>=', $value['start']))
                    ->when($value['end'], fn($query) => $query->where($property, '<=', $value['end']));
            }
            return $query->where($property, $value);
        }
        return parent::filtersExact($query, $value, $property);
    }
}
