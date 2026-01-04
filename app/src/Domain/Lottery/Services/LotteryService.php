<?php

namespace Domain\Lottery\Services;

use Domain\Lottery\DTO\LotteryCollectionDTO;
use Domain\Lottery\DTO\LotteryOneDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class LotteryService
{
    public function __construct(
        private LotterySelectService $lotterySelectService,
        private LotteryProductsService $lotteryProductsService,
    ) {
    }

    public function getLotteries(LotteryCollectionDTO $collectionDTO): Collection
    {
        $lotteries = $this->lotterySelectService->getLotteries($collectionDTO->getLimit());

        if ($collectionDTO->isWithProducts()) {
            $lotteries = $this->setProductsToLotteries($lotteries, $collectionDTO->getProductsLimit());
        }

        return $lotteries;
    }

    public function getLotteriesPaginated(int $limit = null): LengthAwarePaginator
    {
        return $this->lotterySelectService->getLotteriesPaginated($limit);
    }

    public function getOneLottery(LotteryOneDTO $oneDTO): object
    {
        $lottery = $this->lotterySelectService->getOneLottery($oneDTO->getSlug());

        $products = $this->lotteryProductsService->getLotteryProducts(
            [$lottery->id],
            $oneDTO->getProductsLimit(),
        );

        return $this->setProductsToLottery($lottery, $products);
    }

    private function setProductsToLotteries(Collection $lotteries, int $limit): Collection
    {
        $products = $this->lotteryProductsService->getLotteryProducts(
            $lotteries->pluck('id')->toArray(),
            $limit,
        );

        return $lotteries->map(fn (object $lottery) => $this->setProductsToLottery($lottery, $products));
    }

    private function setProductsToLottery(object $lottery, Collection $products): object
    {
        $products = $products->where('lottery_id', $lottery->id)->sortBy('sort');

        $lottery->setAttribute(
            'productsData',
            $products,
        );

        return $lottery;
    }
}
