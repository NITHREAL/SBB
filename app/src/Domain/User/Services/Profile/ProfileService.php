<?php

namespace Domain\User\Services\Profile;

use Domain\User\DTO\Profile\ProfileDTO;
use Domain\User\Models\User;
use Domain\User\Services\Loyalty\Contact\LoyaltyContactService;
use Exception;
use Infrastructure\Services\Auth\Signature;
use Infrastructure\Services\SMS\SmsCodeService;

class ProfileService
{
    private string $signatureName = 'api:v1:user.profile.checkCode';

    private int $signatureTtl = 300;

    public function __construct(
        private readonly SmsCodeService $smsCodeService,
        private readonly LoyaltyContactService  $loyaltyContactService,
    ) {
    }

    public function getPersonalData(User $user): User
    {
        $loyaltyData = $this->loyaltyContactService->getLoyaltyContact($user);

        $profileDTO = ProfileDTO::make($loyaltyData->getProfileData(), $user);

        return $this->updateUserProfile($user, $profileDTO);
    }

    public function updatePersonalData(User $user, ProfileDTO $profileDTO): User
    {
        $user = $this->updateUserProfile($user, $profileDTO);

        $this->loyaltyContactService->updateLoyaltyContact($user);

        return $user;
    }

    /**
     * @throws Exception
     */
    public function updatePhoneCodeSend(string $phone): string
    {
        // TODO временно отключаем отправку СМС
        $code = $this->smsCodeService->processTechCodeSend($phone);

        //$code = $this->smsCodeService->processCodeSend($phone);

        return $this->createSignature($phone, $code);
    }

    public function updatePhone(User $user, string $phone): string
    {
        $user->update([
            'phone' => $phone,
        ]);

        return $phone;
    }

    private function updateUserProfile(User $user, ProfileDTO $profileDTO): User
    {
        $user->fill([
            'first_name'    => $profileDTO->getFirstName(),
            'middle_name'   => $profileDTO->getMiddleName(),
            'last_name'     => $profileDTO->getLastName(),
            'email'         => $profileDTO->getEmail(),
            'sex'           => $profileDTO->getSex(),
            'birthdate'     => $profileDTO->getBirthDate(),
        ]);

        $user->save();

        return $user;
    }

    private function createSignature(string $phone, string $code): string
    {
        return Signature::create(
            $this->signatureName,
            compact('phone', 'code'),
            $this->signatureTtl,
            true,
        );
    }
}
