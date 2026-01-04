<?php

namespace Domain\Lottery\Services;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Lottery\Models\Lottery;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class LotterySelectService
{
    private const CAROUSEL_LIMIT = 5;

    private const PAGINATED_LIMIT = 10;

    public function getLotteries(int $limit = null): Collection
    {
        $limit = $limit ?? self::CAROUSEL_LIMIT;

        $lotteries = Lottery::query()
            ->baseQuery()
            ->whereActive()
            ->limit($limit)
            ->get();

        return $this->prepareLotteries($lotteries);
    }

    public function getLotteriesPaginated(int $limit = null): LengthAwarePaginator
    {
        $limit = $limit ?? self::PAGINATED_LIMIT;

        $lotteries = Lottery::query()
            ->baseQuery()
            ->whereActive()
            ->paginate($limit);

        return $lotteries->setCollection(
            $this->prepareLotteries($lotteries->getCollection()),
        );
    }

    public function getOneLottery(string $slug): object
    {
        $lottery = Lottery::query()->whereSlug($slug)->firstOrFail();

        // Обычные изображения розыгрышей
        $images = Attachment::query()->where('id', $lottery->image_id)->get();

        return $this->prepareLottery($lottery, $images);
    }

    private function prepareLotteries(Collection $lotteries): Collection
    {
        // Маленькие изображения розыгрышей
        $images = Attachment::query()
            ->whereIn(
                'id',
                $lotteries->pluck('mini_image_id')->toArray()
            )
            ->get();

        return $lotteries->map(fn (object $lottery) => $this->prepareLottery($lottery, $images));
    }

    private function prepareLottery(object $lottery, Collection $images): object
    {
        // В зависимости от того какие изображения выбирались
        $image = $images->firstWhere('id', $lottery->image_id)
            ?? $images->firstWhere('id', $lottery->mini_image_id);

        if ($image) {
            $lottery->imageUrl = ImageUrlHelper::getUrl($image);
        }

        return $lottery;
    }
}
