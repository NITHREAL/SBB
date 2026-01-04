<?php

namespace App\Orchid\Screens\References\PaymentType;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\References\PaymentType\PaymentTypeCitiesLayout;
use App\Orchid\Layouts\References\PaymentType\PaymentTypeRowLayout;
use Domain\Order\Models\Payment\PaymentType;
use Domain\Order\Requests\Admin\PaymentType\PaymentTypeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class PaymentTypeEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = '';

    protected PaymentType $payment;

    /**
     * Query data.
     *
     * @param int|null $payment
     * @return array
     */
    public function query(?int $payment = null): array
    {
        $payment = !empty($payment) ? PaymentType::findOrFail($payment) : new PaymentType();

        $payment->load('cities');

        $this->name = $payment->title;
        $this->payment = $payment;

        return [
            'payment' => $payment
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->payment)
        ]);
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                __('admin.payment_type.info')   => new PaymentTypeRowLayout,
                __('admin.payment_type.cities') => new PaymentTypeCitiesLayout,
            ])

        ];
    }

    public function save(PaymentTypeRequest $request): RedirectResponse
    {
        $paymentId = (int) Arr::get($request->route()->parameters(), 'payment', 0);

        $paymentType = !empty($paymentId) ? PaymentType::findOrFail($paymentId) : new PaymentType();

        $data = $request->validated();
        $cities = Arr::get($data, 'payment.cities', []);
        $citiesId = array_column($cities, 'id');

        $paymentType->update($data['payment']);
        $paymentType->cities()->sync($citiesId);

        if ($paymentType->exists) {
            Alert::success('Изменния сохранены');
        }

        return redirect()->route('platform.payment_types.edit', $paymentType->id);
    }
}
