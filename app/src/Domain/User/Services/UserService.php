<?php

namespace Domain\User\Services;

use Domain\Audience\Service\AudienceUsersService;
use Domain\User\DTO\UserCreateDTO;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

readonly class UserService
{
    private AudienceUsersService $audienceUsersService;

    public function __construct(AudienceUsersService $audienceUsersService)
        {
    $this->audienceUsersService = $audienceUsersService;
        }

    public function createUserFromAdmin(UserCreateDTO $userDTO): object
    {
        $user = new User();

        $user->fill([
            'first_name'        => $userDTO->getFirstName(),
            'last_name'         => $userDTO->getLastName(),
            'email'             => $userDTO->getEmail(),
            'password'          => $userDTO->getPassword(),
            'birthdate'         => $userDTO->getBirthDate(),
            'sex'               => $userDTO->getSex(),
            'phone'             => $userDTO->getPhone(),
            'permissions'       => $userDTO->getPermissions(),
            'registration_type' => $userDTO->getRegistrationType(),
        ]);

        $user->save();

        return $user;
    }

    public function updateUserFromAdmin(UserCreateDTO $userDTO, User $user): object
    {
        $user->update([
            'first_name'        => $userDTO->getFirstName(),
            'last_name'         => $userDTO->getLastName(),
            'email'             => $userDTO->getEmail(),
            'birthdate'         => $userDTO->getBirthDate(),
            'phone'             => $userDTO->getPhone(),
        ]);

        return $user;
    }

    public function deleteUser(User $user): void
    {
        DB::transaction(function() use ($user) {
            $this->deleteUserAddresses($user);
            $this->deleteUserProfileData($user);

            $this->audienceUsersService->removeUserFromAudiences($user);

            $user->delete();
        });
    }

    private function deleteUserAddresses(User $user): void
    {
        $user->addresses()->delete();
    }

    private function deleteUserProfileData(User $user): void
    {
        $user->update([
            'phone'             => sprintf('%s|%s', time(), $user->phone),
            'first_name'        => null,
            'last_name'         => 'Пользователь Удалён',
            'sex'               => null,
            'email'             => null,
            'birthday'          => null,
            'password'          => null,
            'remember_token'    => null,
            'set_card_number'   => null
        ]);
    }
}
