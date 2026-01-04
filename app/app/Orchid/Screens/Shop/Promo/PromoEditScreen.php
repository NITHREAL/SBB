<?php

namespace App\Orchid\Screens\Shop\Promo;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Promo\PromoCategoriesLayout;
use App\Orchid\Layouts\Shop\Promo\PromoInfoListener;
use App\Orchid\Layouts\Shop\Promo\PromoInfoRow;
use App\Orchid\Layouts\Shop\Promo\PromoProductsLayout;
use App\Orchid\Layouts\Shop\Promo\PromoUsersLayout;
use App\Orchid\Screens\Traits\SyncHasMany;
use Carbon\Carbon;
use Domain\Promocode\Enums\PromocodeDeliveryTypeEnum;
use Domain\Promocode\Models\Promocode;
use Domain\Promocode\Models\PromocodeUsedPhone;
use Domain\Promocode\Requests\Admin\PromoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PromoEditScreen extends Screen
{
    use SyncHasMany;

    public ?string $name = 'Добавить промокод';

    public ?Promocode $promo = null;

    public function query(Promocode $promo): array
    {
        if ($promo->exists) {
            $promo->load('products', 'users', 'categories');

            $this->name = $promo->code;
        }

        $this->promo = $promo;

        return [
            'promo' => $promo,
        ];
    }

    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->promo),
            new Actions\Dublicate('dublicate', $this->promo),
        ]);
    }

    public function asyncIsFreeDelivery(array $isFreeDeliveryValues = []): array
    {
        return [
            'is_free_delivery' => $isFreeDeliveryValues['free_delivery']
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                __('admin.promo.info') => [
                    PromoInfoRow::class, PromoInfoListener::class
                ],
                __('admin.promo.categories') => PromoCategoriesLayout::class,
                __('admin.promo.products') => PromoProductsLayout::class,
                __('admin.promo.users') => PromoUsersLayout::class,
            ])
        ];
    }

    public function save(Promocode $promo, PromoRequest $request)
    {
        $data = $request->validated()['promo'];
        $categories = Arr::get($data, 'categories', []);
        $products = Arr::get($data, 'products', []);
        $users = Arr::get($data, 'users', []);

        $isFreeDeliveryCode = (bool)Arr::get($data, 'free_delivery', false);

        if ($isFreeDeliveryCode) {
            $data = array_merge([
                'discount' => 0,
                'delivery_type' => PromocodeDeliveryTypeEnum::delivery()->value,
                'any_product' => false,
                'percentage' => false
            ], $data);

            $products = [];
        }

        $userPromo = [];

        foreach ($users as $user) {
            $userPromo[$user['id']] = [
                'max_uses' => $user['pivot']['max_uses'] ?? null
            ];
        }

        $promo->fill($data)->save();
        $promo->categories()->sync(array_column($categories, 'id'));
        $promo->products()->sync(array_column($products, 'id'));
        $promo->users()->sync($userPromo);
        $promo->show_audience_id = $data['show_audience_id'] ?? null;

        $promo->save();

        Alert::success('Промо код успешно сохранен');

        return response()->redirectToRoute('platform.promos.edit', $promo);
    }


    public function dublicate(Request $request)
    {
        $promo = Promocode::with('categories', 'products', 'users')
            ->findOrFail($request->get('id'));
        /** @var Promocode $promo */
        $repplecate = $promo->replicate();
        $repplecate->active = false;
        $repplecate->created_at = Carbon::now();
        $repplecate->save();

//        $promo->load(['categories', 'products', 'users']);

        $phones = $promo->promoUsedPhone->pluck('phone')->toArray();

        $repplecate->categories()->syncWithoutDetaching(
            $promo->categories?->pluck('id')->toArray() ?? []
        );

        $repplecate->products()->syncWithoutDetaching(
            $promo->products?->pluck('id')->toArray() ?? []
        );

        $repplecate->users()->syncWithoutDetaching(
            $promo->users?->pluck('id')->toArray() ?? []
        );

        foreach ($phones as $phone) {
            $promoUsedPhone = new PromocodeUsedPhone();
            $promoUsedPhone->promo_id = $repplecate->id;
            $promoUsedPhone->phone = $phone;
            $promoUsedPhone->save();
        }

        Toast::success(__('Копия создана'));

        return response()->redirectToRoute('platform.promos.edit', $repplecate);
    }
}
