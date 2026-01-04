<?php

namespace Domain\PromoAction\Services;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\PromoAction\Models\PromoAction;
use Illuminate\Support\Collection;

readonly class PromoActionSelectService
{
    private const CAROUSEL_LIMIT = 3;

    public function getPromoActions(int $limit = null): Collection
    {
        $limit = $limit ?? self::CAROUSEL_LIMIT;

        $promoActions = PromoAction::query()
            ->baseQuery()
            ->whereActive()
            ->limit($limit)
            ->get();

        return $this->preparePromoActionsCollection($promoActions);
    }

    public function getOnePromoAction(string $slug): object
    {
        $promoAction = PromoAction::query()->whereSlug($slug)->firstOrFail();

        // Обычные изображения промо акций
        $images = Attachment::query()->where('id', $promoAction->image_id)->get();

        return $this->preparePromoAction($promoAction, $images);
    }

    private function preparePromoActionsCollection(Collection $promoActions): Collection
    {
        // Маленькие изображения промо акций
        $images = Attachment::query()
            ->whereIn(
                'id',
                $promoActions->pluck('mini_image_id')->toArray()
            )
            ->get();

        return $promoActions->map(fn(object $promoAction) => $this->preparePromoAction($promoAction, $images));
    }

    private function preparePromoAction(object $promoAction, Collection $images): object
    {
        // В зависимости от того какие изображения выбирались
        $image = $images->firstWhere('id', $promoAction->image_id)
            ?? $images->firstWhere('id', $promoAction->mini_image_id);

        if ($image) {
            $promoAction->imageUrl = ImageUrlHelper::getUrl($image);
        }

        return $promoAction;
    }
}
