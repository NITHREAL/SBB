<?php

namespace Domain\Lottery\Services;

use Domain\Image\Models\Attachment;
use Domain\Lottery\DTO\LotteryChangeDTO;
use Domain\Lottery\Models\Lottery;

readonly class LotteryChangeService
{
    public function create(LotteryChangeDTO $lotteryDTO): object
    {
        $lottery = $this->getFilledLottery($lotteryDTO);

        $lottery->save();

        return $this->updateRelations($lottery, $lotteryDTO);
    }

    public function update(int $id, LotteryChangeDTO $lotteryDTO): object
    {
        $lottery = $this->getFilledLottery($lotteryDTO, $id);

        $lottery->save();

        return $this->updateRelations($lottery, $lotteryDTO);
    }

    private function getFilledLottery(LotteryChangeDTO $lotteryDTO, int $id = null): object
    {
        $lottery = empty($id)
            ? new Lottery()
            : Lottery::findOrFail($id);

        return $lottery->fill([
            'title'             => $lotteryDTO->getTitle(),
            'description'       => $lotteryDTO->getDescription(),
            'slug'              => $lotteryDTO->getSlug(),
            'active_from'       => $lotteryDTO->getActiveFrom(),
            'active_to'         => $lotteryDTO->getActiveTo(),
            'sort'              => $lotteryDTO->getSort(),
            'active'            => $lotteryDTO->isActive(),
        ]);
    }

    public function updateRelations(object $lottery, LotteryChangeDTO $lotteryDTO): object
    {
        $imageId = $lotteryDTO->getImageId();

        if ($imageId && $image = Attachment::find($imageId)) {
            $lottery->image()->associate($image);
        } else {
            $lottery->image()->dissociate();
        }

        $miniImageId = $lotteryDTO->getMiniImageId();

        if ($miniImageId && $miniImage = Attachment::find($miniImageId)) {
            $lottery->miniImage()->associate($miniImage);
        } else {
            $lottery->miniImage()->dissociate();
        }

        $lottery->save();

        $lottery->products()->sync($lotteryDTO->getProducts());

        return $lottery;
    }
}
