<?php

namespace Domain\User\Services\Settings;

use Domain\User\DTO\Settings\UserSettingsDTO;
use Domain\User\Models\User;
use Domain\User\Models\UserSettings;
use Domain\User\Services\Loyalty\Contact\LoyaltyContactService;

readonly class UserSettingsService
{
    public function __construct(
        private LoyaltyContactService $loyaltyContactService,
    ) {
    }

    public function getUserSettingsData(User $user): UserSettings
    {
        $loyaltySettingsData = $this->loyaltyContactService->getLoyaltyContact($user);

        $userSettings = $user->settings;

        $settingsDTO = UserSettingsDTO::make(
            array_merge(
                $loyaltySettingsData->getSettingsData(),
                ['allowNotifyPush' => (bool) $userSettings?->allow_notify_push],
            ),
            $user
        );

        return $this->updateUserSettings($userSettings, $settingsDTO);
    }

    public function updateUserSettingsData(UserSettingsDTO $dto): UserSettings
    {
        $user = $dto->getUser();

        $userSettings = $this->updateUserSettings($user->settings, $dto);

        $this->loyaltyContactService->updateLoyaltyContact($user);

        return $userSettings;
    }


    public function updateElectronicChecks(bool $electronicChecks, User $user): bool
    {
        $user->electronic_checks = $electronicChecks;
        $user->save();

        return $user->electronic_checks;
    }


    public function updateAutoBrightness(bool $autoBrightness, User $user): bool
    {
        $user->auto_brightness = $autoBrightness;
        $user->save();

        return $user->auto_brightness;
    }

    public function updateNewsSubscription(bool $newsSubscription, User $user): bool
    {
        $user->settings()->news_subscription = $newsSubscription;
        $user->save();

        return (bool) $user->news_subscription;
    }

    private function updateUserSettings(UserSettings $userSettings, UserSettingsDTO $settingsDTO): object
    {
        $userSettings = $this->getFilledSettings($userSettings, $settingsDTO);

        $userSettings->save();

        return $userSettings;
    }

    private function getFilledSettings(UserSettings $userSettings, UserSettingsDTO $dto): UserSettings
    {
        return $userSettings->fill([
            'allow_notify'          => $dto->getAllowNotify(),
            'allow_notify_push'     => $dto->getAllowNotifyPush(),
            'allow_notify_sms'      => $dto->getAllowNotifySms(),
            'allow_notify_email'    => $dto->getAllowNotifyEmail(),
            'allow_phone_calls'     => $dto->getAllowPhoneCalls(),
        ]);
    }
}
