<?php

namespace Domain\Farmer\Services;

use Domain\Farmer\Models\Farmer;
use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Services\ImageSelection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FarmerService
{
    private const DEFAULT_FARMERS_LIMIT = 20;

    public function __construct(
        private readonly FarmerProductsService $farmerProductsService,
        private readonly FarmerReviewService $farmerReviewService,
        private readonly FarmerImagesService $farmerImagesService,
    ) {
    }

    public function getFarmers(
        ?int $limit
    ): LengthAwarePaginator {
        $limit = empty($limit) ? self::DEFAULT_FARMERS_LIMIT : $limit;

        $store1cId = BuyerStore::getOneCId();

        $farmers = Farmer::query()
            ->withProductsInStoreQuery($store1cId)
            ->orderBy('farmers.sort')
            ->paginate($limit);

        return $this->getPreparedPaginatedFarmersCollection($farmers);
    }

    public function getFarmerBySlug(string $slug, ?int $limit): array
    {
        $limit = empty($limit) ? self::DEFAULT_FARMERS_LIMIT : $limit;

        $store1cId = BuyerStore::getOneCId();

        $farmer = Farmer::query()->whereSlug($slug)->first();

        if (is_null($farmer)) {
            throw new NotFoundHttpException("фермера с slug: $slug не существует");
        }

        $images = ImageSelection::getFarmersImages([$farmer->id]);

        $reviews = $this->farmerReviewService->getFarmersProductsReviews([$farmer->id]);

        $categories = $this->farmerProductsService->getFarmerProducts($farmer, $limit, $store1cId);

        $farmerInfo = $this->getPreparedFarmerCertificates($farmer, $images, $reviews);

        return compact('farmerInfo', 'categories');
    }

    public function getFarmerForProduct(object $product): object
    {
        $farmer = Farmer::query()->where('id', $product->farmer_id)->first();

        $images = ImageSelection::getFarmersImages([$farmer->id]);

        return $this->farmerImagesService->setImage($farmer, $images);
    }

    private function getPreparedPaginatedFarmersCollection(LengthAwarePaginator $farmers): LengthAwarePaginator
    {
        return $farmers->setCollection(
            $this->getPreparedFarmersCollection($farmers->getCollection())
        );
    }

    private function getPreparedFarmersCollection(Collection $farmers): Collection
    {
        $farmersIds = $farmers->pluck('id')->toArray();

        $images = ImageSelection::getFarmersImages($farmersIds);

        $reviews = $this->farmerReviewService->getFarmersProductsReviews($farmersIds);

        return $farmers
            ->map(function ($item) use ($images, $reviews) {
                return $this->getPreparedFarmer($item, $images, $reviews);
            });
    }

    private function getPreparedFarmer(object $farmer, Collection $images, Collection $reviews): object
    {
        $farmer->setAttribute(
            'reviewCount',
            $reviews
                ->where('farmerId', $farmer->id)
                ->count(),
        );

        return $this->farmerImagesService->setImage($farmer, $images);
    }

    private function getPreparedFarmerCertificates(object $farmer, Collection $images, Collection $reviews): object
    {
        $farmerInfo = $this->getPreparedFarmer($farmer, $images, $reviews);

        return $this->farmerImagesService->setCertificatesImages($farmerInfo, $images);
    }
}
