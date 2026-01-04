<?php

namespace App\Orchid\Screens\References\PaymentType;

use App\Orchid\Layouts\References\PaymentType\PaymentTypeListLayout;
use Domain\Order\Models\Payment\PaymentType;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class PaymentTypeListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $this->name = __('admin.payment_types');

        return [
            'payments' => PaymentType::query()
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            PaymentTypeListLayout::class
        ];
    }

    public function activate(Request $request): void
    {
        PaymentType::findOrFail($request->get('id'))->activate($request->get('activate',false));
    }
}
