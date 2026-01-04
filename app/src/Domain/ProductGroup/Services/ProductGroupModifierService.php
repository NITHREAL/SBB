<?php

namespace Domain\ProductGroup\Services;

use Domain\Audience\Models\Audience;
use Domain\Image\Models\Attachment;
use Domain\ProductGroup\DTO\ProductGroupCreateDTO;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\ProductGroup\Services\Products\ProductGroupProductsService;
use Domain\ProductGroup\Services\Tags\ProductGroupTagsService;
use Domain\Story\Models\Story;
use Exception;

class ProductGroupModifierService
{
    private array $availabilityColumns = [
        'active',
        'site',
        'mobile',
    ];

    public function __construct(
        private readonly ProductGroupProductsService $groupProductsService,
        private readonly ProductGroupTagsService $groupTagsService,
    ) {
    }

    public function createGroup(ProductGroupCreateDTO $dto): object
    {
        $group = $this->getFilledGroupInstance(
            new ProductGroup(),
            $dto
        );

        $group->save();

        $this->updateGroupRelations($group, $dto);

        return $group;
    }

    public function updateGroup(int $id, ProductGroupCreateDTO $dto): object
    {
        /**
         * @var ProductGroup $group
         */
        $group = ProductGroup::findOrFail($id);

        $group = $this->getFilledGroupInstance(
            $group,
            $dto
        );

        $group->save();

        $this->updateGroupRelations($group, $dto);

        return $group;
    }

    public function delete(int $id): bool
    {
        return ProductGroup::query()->whereId($id)->delete($id);
    }

    private function getFilledGroupInstance(ProductGroup $group, ProductGroupCreateDTO $dto): ProductGroup
    {
        $group->fill([
            'title'     => $dto->getTitle(),
            'slug'      => $dto->getSlug(),
            'active'    => $dto->isActive(),
            'site'      => $dto->availableForSite(),
            'mobile'    => $dto->availableForMobile(),
            'sort'      => $dto->getSort(),
        ]);

        return $group;
    }

    private function updateGroupRelations(ProductGroup $group, ProductGroupCreateDTO $dto): void
    {
        if ($dto->getAudienceId()) {
            $audience = Audience::findOrFail($dto->getAudienceId());
            $group->audience()->associate($audience);
        } else {
            $group->audience()->dissociate();
        }

        if ($dto->getStoryId()) {
            $story = Story::findOrFail($dto->getStoryId());
            $group->story()->associate($story);
        } else {
            $group->story()->dissociate();
        }

        if ($dto->getBackgroundImageId()) {
            $backgroundImage = Attachment::findOrFail($dto->getBackgroundImageId());
            $group->backgroundImage()->associate($backgroundImage);
        } else {
            $group->backgroundImage()->dissociate();
        }

        $group->images()->sync($dto->getImages());

        $this->groupProductsService->updateProductGroupProductsRelation($group, $dto->getProducts());
        $this->groupTagsService->updateProductGroupTagsRelation($group, $dto->getTags());

        $group->save();
    }

    /**
     * @throws Exception
     */
    public function changeAvailabilityColumn(
        ProductGroup $productGroup,
        string $column,
        bool $value,
    ): void {
        if (!in_array($column, $this->availabilityColumns)) {
            throw new Exception('Вы пытаетесь изменить несуществующее свойство');
        }

        $productGroup->$column = $value;
        $productGroup->save();
    }
}
