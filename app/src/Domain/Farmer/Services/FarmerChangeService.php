<?php

namespace Domain\Farmer\Services;

use Domain\Farmer\DTO\FarmerDTO;
use Domain\Farmer\Models\Farmer;
use Domain\Image\Models\Attachment;

class FarmerChangeService
{
    public function updateFarmer(int $farmerId, FarmerDTO $dto): Farmer
    {
        /** @var Farmer $farmer */
        $farmer = Farmer::findOrFail($farmerId);

        $farmer->setMetaTagValues($dto->getMetaTagValues());

        $farmer = $this->getFilledFarmerInstance(
            $farmer,
            $dto,
        );

        $this->updateFarmerRelations($farmer, $dto);

        return $farmer;
    }

    private function updateFarmerRelations(
        Farmer    $farmer,
        FarmerDTO $dto,
    ): void {

        $certificatesIds = $this->getPreparedCertificates($dto->getCertificates());

        $imagesIds = !empty($dto->getImage())
            ? array_merge($certificatesIds, [$dto->getImage()])
            : $certificatesIds;

        $farmer->certificates()->sync($imagesIds);

        $farmer->save();
    }

    private function getFilledFarmerInstance(
        Farmer    $farmer,
        FarmerDTO $dto,
    ): Farmer {
        $farmer->fill([
            'address' => $dto->getAddress(),
            'sort' => $dto->getSort(),
            'slug' => $dto->getSlug(),
            'certificates' => $dto->getCertificates(),
        ]);

        return $farmer;
    }

    private function getPreparedCertificates(?array $certificates): array
    {
        $certificatesPrepared = [];

        foreach ($certificates as $certificate) {
            $key = !empty($certificate['id']) ? 'id' : 'url';

            $image = Attachment::query()->where('id', $certificate[$key])->first();
            $image->setAttribute('description', $certificate['description']);
            $image->save();
            $certificatesPrepared[] = $image->id;
        }

        return $certificatesPrepared;
    }
}
