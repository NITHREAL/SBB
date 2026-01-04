<?php

namespace Domain\PromoAction\Services;

use Domain\Image\Models\Attachment;
use Domain\PromoAction\DTO\PromoActionChangeDTO;
use Domain\PromoAction\Models\PromoAction;

readonly class PromoActionChangeService
{
    public function create(PromoActionChangeDTO $promoActionDTO): object
    {
        $promoAction = $this->getFilledPromoAction($promoActionDTO);

        $promoAction->save();

        return $this->updateRelations($promoAction, $promoActionDTO);
    }

    public function update(int $id, PromoActionChangeDTO $promoActionDTO): object
    {
        $promoAction = $this->getFilledPromoAction($promoActionDTO, $id);

        $promoAction->save();

        return $this->updateRelations($promoAction, $promoActionDTO);
    }

    private function getFilledPromoAction(PromoActionChangeDTO $promoActionDTO, int $id = null): object
    {
        $promoAction = empty($id)
            ? new PromoAction()
            : PromoAction::findOrFail($id);

        return $promoAction->fill([
            'title'             => $promoActionDTO->getTitle(),
            'description'       => $promoActionDTO->getDescription(),
            'short_description' => $promoActionDTO->getShortDescription(),
            'slug'              => $promoActionDTO->getSlug(),
            'active_from'       => $promoActionDTO->getActiveFrom(),
            'active_to'         => $promoActionDTO->getActiveTo(),
            'sort'              => $promoActionDTO->getSort(),
            'active'            => $promoActionDTO->isActive(),
        ]);
    }

    private function updateRelations(PromoAction $promoAction, PromoActionChangeDTO $promoActionDTO): object
    {
        $imageId = $promoActionDTO->getImageId();

        if ($imageId && $image = Attachment::find($imageId)) {
            $promoAction->image()->associate($image);
        } else {
            $promoAction->image()->dissociate();
        }

        $miniImageId = $promoActionDTO->getMiniImageId();

        if ($miniImageId && $miniImage = Attachment::find($miniImageId)) {
            $promoAction->miniImage()->associate($miniImage);
        } else {
            $promoAction->miniImage()->dissociate();
        }

        $promoAction->save();

        $promoAction->products()->sync($promoActionDTO->getProducts());

        return $promoAction;
    }
}
