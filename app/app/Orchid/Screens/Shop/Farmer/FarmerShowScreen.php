<?php

namespace App\Orchid\Screens\Shop\Farmer;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Farmer\FarmerImageRow;
use App\Orchid\Layouts\Shop\Farmer\FarmerImagesRow;
use App\Orchid\Layouts\Shop\Farmer\FarmerInfoRow;
use Domain\Farmer\DTO\FarmerDTO;
use Domain\Farmer\Models\Farmer;
use Domain\Farmer\Requests\Admin\FarmerRequest;
use Domain\Farmer\Services\FarmerChangeService;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class FarmerShowScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Добавить фермера';

    public ?Farmer $farmer = null;

    public function __construct(
        protected FarmerChangeService $farmerCategoryService,
    ) {
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Farmer $farmer): array
    {
        if ($farmer->exists) {
            $farmer->load('metaTagValues');

            $this->name = $farmer->name;
        }

        $this->farmer = $farmer;

        return [
            'farmer' => $farmer,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->farmer)
        ]);
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
                __('admin.category.info') => FarmerInfoRow::class,
                'Сертификаты' => new FarmerImageRow($this->farmer),
                'Изображении' => new FarmerImagesRow($this->farmer),
            ]),
        ];
    }

    public function save(FarmerRequest $request): void
    {
        $farmerId = (int) Arr::get($request->route()->parameters(), 'farmer', 0);

        $data = Arr::get($request->all(), 'farmer', []);

        $farmerDTO = FarmerDTO::make($data);

        if ($farmerId) {
            $this->farmerCategoryService->updateFarmer($farmerId, $farmerDTO);
            Toast::success(sprintf(
                '%s %u %s',
                'Фермер с id',
                $farmerId,
                'сохранен',
            ));
        } else {
            Toast::error(
                sprintf(
                    '%s %u% s',
                    'Фермер с id',
                    $farmerId,
                    'не найден',
                )
            );
        }
    }
}
