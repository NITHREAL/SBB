<?php

namespace Domain\Promocode\Service;

use Domain\Promocode\DTO\GetFirstOrderPromocodeDTO;
use Domain\Promocode\DTO\GetPromocodesDTO;
use Domain\Promocode\Enums\PromocodeOrderTypeEnum;
use Domain\Promocode\Exceptions\FirstOrderPromocodeException;
use Domain\Promocode\Models\Promocode;
use Illuminate\Support\Collection;

class PromocodeSelectionService
{
    public function getPromocodes(GetPromocodesDTO $dto): Collection
    {
        return Promocode::query()
            ->select(['promos.*'])
            ->whereActive()
            ->whereActual()
            ->whereAudienceUser($dto->getUserId())
            ->when($dto->isMobile(), function ($query) {
                return $query->whereMobile();
            })
            ->get();
    }

    /**
     * @throws FirstOrderPromocodeException
     */
    public function getFirstOrderPromocode(GetFirstOrderPromocodeDTO $dto): Promocode
    {
        $promocode =  Promocode::query()
            ->select(['promos.*'])
            ->whereActive()
            ->whereActual()
            ->whereOrderType(PromocodeOrderTypeEnum::first())
            ->when($dto->isMobile(), function ($query) {
                return $query->whereMobile();
            })
            ->first();

        if (!$promocode) {
            throw new FirstOrderPromocodeException();
        }

        return $promocode;
    }
}
