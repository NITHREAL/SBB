<?php

namespace Domain\User\Services\Loyalty;

use Domain\User\Models\User;
use Domain\User\Models\UserSettings;
use Domain\User\Services\Loyalty\Contact\LoyaltyContactService;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\GetContactResponse;

readonly class LoyaltyUserDataService
{
    public function __construct(
        private LoyaltyContactService $loyaltyContactService,
    ) {
    }

    public function setUserLoyaltyData(
        User $user,
        string $sessionId,
        string $loyaltyId,
    ): void {
        $user->fill([
            'loyalty_session_id'    => $sessionId,
            'loyalty_id'            => $loyaltyId,
        ]);

        $user->save();
    }

    public function getUpdatedUserFromLoyalty(User $user): User
    {
        $loyaltyData = $this->loyaltyContactService->getLoyaltyContact($user);

        return $this->updateUserDataFromLoyalty($loyaltyData, $user);
    }

    public function updateUserDataFromLoyalty(GetContactResponse $getContactResponse, User $user): User
    {
        $user->fill([
            'firstName'                 => $getContactResponse->getFirstName(),
            'lastName'                  => $getContactResponse->getLastName(),
            'middleName'                => $getContactResponse->getMiddleName(),
            'sex'                       => $getContactResponse->getGender(),
            'birthdate'                 => $getContactResponse->getBirthDate(),
            'email'                     => $getContactResponse->getEmail(),
            'bonuses'                   => $getContactResponse->getBalance(),
            'loyalty_level_id'          => $getContactResponse->getLevelId(),
            'loyalty_level_progression' => $getContactResponse->getLevelProgress(),
        ]);

        $user->save();

        return $user;
    }

    public function updateUserSettingsFromLoyalty(
        GetContactResponse $getContactResponse,
        UserSettings $userSettings,
    ): UserSettings {
        $userSettings->fill([
            'allow_notify'          => $getContactResponse->getAllowNotifications(),
            'allow_notify_email'    => $getContactResponse->getAllowEmail(),
            'allow_phone_calls'     => $getContactResponse->getAllowPhone(),
            'allow_notify_sms'      => $getContactResponse->getAllowSms(),
        ]);

        $userSettings->save();

        return $userSettings;
    }
}
