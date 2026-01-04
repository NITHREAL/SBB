<?php

namespace App\Orchid\Filters\User;

use App\Orchid\Fields\User\PhoneInput;
use App\Orchid\Filters\Basic\BasicFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Infrastructure\Helpers\PhoneFormatterHelper;
use Orchid\Screen\Field;

class PhoneFilter extends BasicFilter
{
    public $parameters = [
        'phone',
    ];

    public function name(): string
    {
        return __('admin.user.phone');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $phone = $this->request->get('phone');
        $phone = PhoneFormatterHelper::unformat($phone);

        return $builder->where('phone', 'LIKE', '%' . $phone . '%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            PhoneInput::make('phone')
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $phone = $this->request->get('phone');
        $phone = PhoneFormatterHelper::format($phone);

        return $this->name() . ': ' . $phone;
    }
}
