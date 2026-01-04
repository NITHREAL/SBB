<?php

namespace Domain\User\Services\Loyalty\Contact;

use Domain\User\Models\User;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact\GetContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact\UpdateContactDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\GetContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\UpdateContactResponse;

readonly class LoyaltyContactService
{
    public function getLoyaltyContact(User $user): GetContactResponse
    {
        $getContactDTO = GetContactDTO::make([
            'sessionId' => $user->loyalty_session_id,
            'userId'    => $user->loyalty_id,
        ]);

        /** @var GetContactResponse $response */
        $response = Loyalty::getContact($getContactDTO);

        return $response;
    }

    public function updateLoyaltyContact(User $user): UpdateContactResponse
    {
        $settings = $user->settings;

        $updateContactDTO = UpdateContactDTO::make([
            'sessionId'         => $user->loyalty_session_id,
            'loyaltyUserId'     => $user->loyalty_id,
            'lastName'          => $user->last_name,
            'firstName'         => $user->first_name,
            'middleName'        => $user->middle_name,
            'gender'            => $user->sex,
            'birthdate'         => $user->birthdate,
            'email'             => $user->email,
            'allowEmail'        => $settings->allow_notify_email,
            'allowSms'          => $settings->allow_notify_sms,
            'allowPhone'        => $settings->allow_phone_calls,
            'allowNotification' => $settings->allow_notify,
        ]);

        /** @var UpdateContactResponse $response */
        $response = Loyalty::updateContact($updateContactDTO);

        return $response;
    }
}
