<?php

namespace Infrastructure\Services\Loyalty\Gateways\Manzana;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Infrastructure\Constants\HttpMethods;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayException;
use Infrastructure\Services\Loyalty\Client\ManzanaClient;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth\ConfirmSMSAuthDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth\RequestSMSAuthDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Bonuses\GetContactBonusesHistoryDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\AddCardToContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\AddVirtualCardToContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Cards\GetContactCardsDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact\GetContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact\UpdateContactDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetFavoriteCategoryDetailDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\GetUserFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories\SetUserFavoriteCategoriesDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetContactLevelInfoDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetContactLevelsDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Levels\GetLevelsDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Auth\ConfirmSmsAuthResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Auth\RequestSmsAuthResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Bonuses\GetContactBonusesHistoryResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\AddCardToContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\AddVirtualCardToContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Cards\GetContactCardsResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\GetContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Contact\UpdateContactResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetAll\GetFavoriteCategoriesResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetDetail\GetFavoriteCategoryDetailResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\GetForUser\GetUserFavoriteCategoriesResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\FavoriteCategories\SetUserFavoriteCategoryResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevelInfo\GetContactLevelsInfoResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\ContactLevels\GetContactLevelsResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Levels\Levels\GetLevelsResponse;

class Manzana implements ManzanaGatewayInterface
{
    private string $customerHost;

    private string $managerHost;

    private string $administratorHost;

    private readonly string $processingHost;

    private readonly string $partnerId;

    private readonly string $testSMSCode;

    private readonly string $virtualCardTypeId;

    private ManzanaClient $client;

    public function __construct(array $config) {
        $this->client = app()->make(ManzanaClient::class);

        $this->customerHost = Arr::get($config, 'customer');
        $this->managerHost = Arr::get($config, 'manager');
        $this->administratorHost = Arr::get($config, 'administrator');
        $this->processingHost = Arr::get($config, 'processing');
        $this->partnerId = Arr::get($config, 'partner_id');
        $this->testSMSCode = Arr::get($config, 'test_sms_code');
        $this->virtualCardTypeId = Arr::get($config, 'virtual_card_type_id');
    }

    /**
     * @throws GatewayException
     */
    public function requestSmsAuth(RequestSMSAuthDTO $registerSmsDTO): RequestSmsAuthResponse
    {
        $params = [
            'Phone'     => $registerSmsDTO->getPhone(),
            'PartnerId' => $this->partnerId,
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Identity/AdvancedRequestSMSLogin');

        $response = $this->client->send($url, $params, HttpMethods::POST);

        return RequestSmsAuthResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function confirmSmsAuth(ConfirmSMSAuthDTO $confirmSmsDTO): ConfirmSmsAuthResponse
    {
        $smsCode = app()->isProduction()
            ? $confirmSmsDTO->getCode()
            : $this->testSMSCode;

        $params = [
            'Token'             => $confirmSmsDTO->getToken(),
            'TemporaryPassword' => $smsCode,
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Identity/AdvancedConfirmSMSLogin');

        $response = $this->client->send($url, $params, HttpMethods::POST);

        return ConfirmSmsAuthResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getContact(GetContactDTO $getContactDTO): GetContactResponse
    {
        $params = [
            'sessionId' => $getContactDTO->getSessionId(),
            'id'        => $getContactDTO->getUserId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Contact/Get');

        $response = $this->client->send($url, $params);

        return GetContactResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function updateContact(UpdateContactDTO $updateContactDTO): UpdateContactResponse
    {
        $params = [
            'SessionId' => $updateContactDTO->getSessionId(),
            'Entity'    => [
                'Id'                => $updateContactDTO->getLoyaltyUserId(),
                'LastName'          => $updateContactDTO->getLastName(),
                'FirstName'         => $updateContactDTO->getFirstName(),
                'MiddleName'        => $updateContactDTO->getMiddleName(),
                'GenderCode'        => $updateContactDTO->getGenderCode(),
                'BirthDate'         => $updateContactDTO->getBirthDate(),
                'EmailAddress'      => $updateContactDTO->getEmail(),
                'AllowEmail'        => $updateContactDTO->isAllowEmail(),
                'AllowSms'          => $updateContactDTO->isAllowSms(),
                'AllowPhone'        => $updateContactDTO->isAllowPhone(),
                'AllowNotification' => $updateContactDTO->isAllowNotification(),
            ],
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Contact/Update');

        $response = $this->client->send($url, $params, HttpMethods::POST);

        return UpdateContactResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getContactBonusesHistory(GetContactBonusesHistoryDTO $contactBonusesHistoryDTO): GetContactBonusesHistoryResponse
    {
        $params = [
            'sessionId' => $contactBonusesHistoryDTO->getSessionId(),
            'contactId' => $contactBonusesHistoryDTO->getContactId(),
            'Take'      => $contactBonusesHistoryDTO->getTake(),
            'Skip'      => $contactBonusesHistoryDTO->getSkip(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Bonus/GetAllByContact');

        $response = $this->client->send($url, $params);

        return GetContactBonusesHistoryResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getContactLevels(GetContactLevelsDTO $getLevelsDTO): GetContactLevelsResponse
    {
        $params = [
            'sessionId'         => $getLevelsDTO->getSessionId(),
            'ContactId'         => $getLevelsDTO->getContactId(),
            'settingLevelId'    => $getLevelsDTO->getSettingLevelId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Level/GetAll');

        $response = $this->client->send($url, $params);

        return GetContactLevelsResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getContactLevelsInfo(GetContactLevelInfoDTO $getContactLevelInfoDTO): GetContactLevelsInfoResponse
    {
        $params = [
            'sessionId'         => $getContactLevelInfoDTO->getSessionId(),
            'ContactId'         => $getContactLevelInfoDTO->getContactId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'LevelAccumulation/GetAllByContact');

        $response = $this->client->send($url, $params);

        return GetContactLevelsInfoResponse::make($response);
    }

    public function getLevels(GetLevelsDTO $getLevelsDTO): GetLevelsResponse
    {
        $params = [
            'sessionId' => $getLevelsDTO->getSessionId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Level/GetDirectory');

        $response = $this->client->send($url, $params);

        return GetLevelsResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getContactCards(GetContactCardsDTO $getContactCardsDTO): GetContactCardsResponse
    {
        $params = [
            'sessionId' => $getContactCardsDTO->getSessionId(),
            'contactId' => $getContactCardsDTO->getContactId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Card/GetAllByContact');

        $response = $this->client->send($url, $params);

        return GetContactCardsResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function addCardToContact(AddCardToContactDTO $addCardToContactDTO): AddCardToContactResponse
    {
        $params = [
            'sessionId'     => $addCardToContactDTO->getSessionId(),
            'contactId'     => $addCardToContactDTO->getContactId(),
            'PartnerId'     => $this->partnerId,
            'CardNumber'    => $addCardToContactDTO->getCardNumber(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Card/BindCard');

        $response = $this->client->send($url, $params, HttpMethods::POST);

        return AddCardToContactResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function addVirtualCardToContact(AddVirtualCardToContactDTO $addVirtualCardToContactDTO): AddVirtualCardToContactResponse
    {
        $params = [
            'sessionId'         => $addVirtualCardToContactDTO->getSessionId(),
            'contactId'         => $addVirtualCardToContactDTO->getContactId(),
            'PartnerId'         => $this->partnerId,
            'VirtualCardTypeId' => $this->virtualCardTypeId,
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'Card/BindVirtualCard');

        $response = $this->client->send($url, $params, HttpMethods::POST);

        if ($error = Arr::get($response, 'odata.error')) {
            Log::channel('loyalty')->error(Arr::get(Arr::get($error, 'message'), 'value'));
        }

        return AddVirtualCardToContactResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getAllFavoriteCategories(GetFavoriteCategoriesDTO $getFavoriteCategoriesDTO): GetFavoriteCategoriesResponse
    {
        $params = [
            'sessionId'         => $getFavoriteCategoriesDTO->sessionId(),
            'OrganizationId'    => $this->partnerId,
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'PersonalCampaignSettings/GetAll');

        $response = $this->client->send($url, $params);

        if ($error = Arr::get($response, 'odata.error')) {
            Log::channel('loyalty')->error(Arr::get(Arr::get($error, 'message'), 'value'));
        }

        return GetFavoriteCategoriesResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function getFavoriteCategoryDetail(GetFavoriteCategoryDetailDTO $getFavoriteCategoryDetailDTO): GetFavoriteCategoryDetailResponse
    {
        $params = [
            'sessionId'             => $getFavoriteCategoryDetailDTO->getSessionId(),
            'OrganizationId'        => $this->partnerId,
            'productlistgroupId'    => $getFavoriteCategoryDetailDTO->getProductlistgroupId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'ProductList/GetAllByGroup');

        $response = $this->client->send($url, $params);

        if ($error = Arr::get($response, 'odata.error')) {
            Log::channel('loyalty')->error(Arr::get(Arr::get($error, 'message'), 'value'));
        }

        return GetFavoriteCategoryDetailResponse::make($response);
    }

    /**
     * @throws GatewayException
     */
    public function setUserFavoriteCategory(SetUserFavoriteCategoriesDTO $setUserFavoriteCategoriesDTO): SetUserFavoriteCategoryResponse
    {
        $params = [
            'SessionId'                     => $setUserFavoriteCategoriesDTO->getSessionId(),
            'ContactId'                     => $setUserFavoriteCategoriesDTO->getContactId(),
            'OrganizationId'                => $this->partnerId,
            'PersonalCampaignSettingsId'    => $setUserFavoriteCategoriesDTO->getPersonalCampaignSettingsId(),

        ];

        $url = sprintf('%s/%s', $this->customerHost, 'FavoriteProduct/Create');

        $response = $this->client->send($url, $params, HttpMethods::POST);

        if ($error = Arr::get($response, 'odata.error')) {
            Log::channel('loyalty')->error(Arr::get(Arr::get($error, 'message'), 'value'));
        }

        return SetUserFavoriteCategoryResponse::make($response);
    }

    public function getUserFavoriteCategories(GetUserFavoriteCategoriesDTO $getUserFavoriteCategoriesDTO): GetUserFavoriteCategoriesResponse
    {
        $params = [
            'SessionId'                     => $getUserFavoriteCategoriesDTO->getSessionId(),
            'ContactId'                     => $getUserFavoriteCategoriesDTO->getContactId(),
            'OrganizationId'                => $this->partnerId,
            'PersonalCampaignSettingsId'    => $getUserFavoriteCategoriesDTO->getPersonalCampaignSettingsId(),
        ];

        $url = sprintf('%s/%s', $this->customerHost, 'FavoriteProduct/GetAllByContact');

        $response = $this->client->send($url, $params);

        if ($error = Arr::get($response, 'odata.error')) {
            Log::channel('loyalty')->error(Arr::get(Arr::get($error, 'message'), 'value'));
        }

        return GetUserFavoriteCategoriesResponse::make($response);
    }
}
