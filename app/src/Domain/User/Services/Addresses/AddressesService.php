<?php

namespace Domain\User\Services\Addresses;

use Domain\City\Models\City;
use Domain\User\DTO\Address\UserAddressDTO;
use Domain\User\Enums\AddressEntranceVariantsEnum;
use Domain\User\Exceptions\UserAddressException;
use Domain\User\Models\UserAddress;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\DaData\Address\DaDataAddressService;

class AddressesService
{
    public function __construct(
        private readonly DaDataAddressService $daDataAddressService,
    ) {
    }

    public function getListByUser(int $userId): Collection
    {
        return UserAddress::query()
            ->whereUser($userId)
            ->get();
    }

    public function getOneById(int $id): object
    {
        return UserAddress::findOrFail($id);
    }

    public function getOneByAddress(int $userId, string $address): ?object
    {
        return UserAddress::query()
            ->whereUser($userId)
            ->whereAddress($address)
            ->first();
    }

    public function createUserAddress(UserAddressDTO $addressDTO): object
    {
        $userAddress = new UserAddress();

        $userAddress = $this->getFilledUserAddress($addressDTO, $userAddress);

        $userAddress->user()->associate($addressDTO->getUser());
        $userAddress->city()->associate($addressDTO->getCity());

        $userAddress->save();

        return $userAddress;
    }

    public function updateUserAddress(int $addressId, UserAddressDTO $addressDTO): object
    {
        $userAddress = UserAddress::findOrFail($addressId);

        if ($userAddress->user_id !== $addressDTO->getUser()->id) {
            throw new \InvalidArgumentException(__('messages.invalid_user_for_address_record'));
        }

        $userAddress = $this->getFilledUserAddress($addressDTO, $userAddress);

        $userAddress->city()->associate($addressDTO->getCity());

        $userAddress->save();

        return $userAddress;
    }

    public function deleteUserAddress(int $id, int $userId): bool
    {
        $userAddress = UserAddress::find($id);

        if ($userAddress->user_id !== $userId) {
            throw new \InvalidArgumentException(__('messages.invalid_user_for_address_record'));
        }

        return $userAddress->delete();
    }

    public function isUserAddressExists(
        int $userId,
        string $address,
        array $exceptedIds = [],
    ): bool {
        return UserAddress::isUserAddressExists($userId, $address, $exceptedIds);
    }

    public function setAddressChosen(string $address, int $userId): void
    {
        try {
            DB::beginTransaction();

            $currentChosenAddress = UserAddress::query()
                ->whereUser($userId)
                ->whereAddress($address)
                ->whereChosen()
                ->first();

            if (empty($currentChosenAddress) || $currentChosenAddress->address !== $address) {
                UserAddress::query()->whereUser($userId)->whereAddress($address)->update(['chosen' => true]);

                if ($currentChosenAddress) {
                    $currentChosenAddress->chosen = false;
                    $currentChosenAddress->save();
                }
            }

            DB::commit();
        } catch (UserAddressException $exception) {
            DB::rollBack();

            $message = sprintf(
                'Ошибка во время изменения состояния "выбранный" у адреса [%s] пользователя [%s]. Ошибка - [%s]',
                $address,
                $userId,
                $exception->getMessage(),
            );

            Log::error($message);
        }
    }

    public function getEntranceVariants(): Collection
    {
        return collect(AddressEntranceVariantsEnum::toArray())->map(function ($item, $key) {
            return collect([
                'label' => $item,
                'value' => $key,
            ]);
        })->values();
    }


    private function getFilledUserAddress(UserAddressDTO $dto, UserAddress $userAddress): object
    {
        $exceptedIds = $userAddress->id ? [$userAddress->id] : [];

        if ($this->isUserAddressExists($dto->getUser()->id, $dto->getAddress(), $exceptedIds)) {
            throw new HttpResponseException(response()->json(
                ['error' => __('validation.user_address.address.unique')],
                422,
            ));
        }

        $address = $dto->getAddress();

        $addressData = $this->daDataAddressService->getOneAddressDataByQuery($dto->getCity(), $address);

        return $userAddress->fill([
            'address'               => $address,
            'city_name'             => $dto->getCityName(),
            'street'                => $dto->getStreet(),
            'house'                 => $dto->getHouse(),
            'building'              => $dto->getBuilding(),
            'entrance'              => $dto->getEntrance(),
            'intercom'              => $dto->getIntercom(),
            'apartment'             => $dto->getApartment(),
            'floor'                 => $dto->getFloor(),
            'comment'               => $dto->getComment(),
            'other_customer'        => $dto->getIsOtherCustomer(),
            'other_customer_phone'  => $dto->getOtherCustomerPhone(),
            'other_customer_name'   => $dto->getOtherCustomerName(),
            'is_main'               => $dto->getIsMain(),
            'has_not_intercom'      => $dto->getHasNotIntercom(),
            'entrance_variant'      => $dto->getEntranceVariant(),
            'latitude'              => Arr::get($addressData, 'latitude'),
            'longitude'             => Arr::get($addressData, 'longitude'),
        ]);
    }
}
