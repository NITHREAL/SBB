<?php

namespace Domain\User\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self wherePhone(string $phone)
 */
class UserQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): self
    {
        return $this
            ->select([
                'users.id',
                'users.phone',
                'users.first_name',
                'users.middle_name',
                'users.last_name',
                'users.email',
                'users.birthdate',
                'users.bonuses',
                'users.referral_code',
                'users.password',
                'users.permissions',
                'users.created_at',
                'users.updated_at',
                'users.set_card_number',
                'users.electronic_checks',
                'users.registration_type',
                'users.active',
            ]);
    }
    public function wherePhone(string $phone): self
    {
        return $this->where('phone', $phone);
    }

    public function isAdmin(): self
    {
        return $this
            ->addSelect([
                'roles.id as role_id',
                'roles.name',
                'roles.slug',
            ])
            ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
            ->leftJoin('roles', 'role_users.role_id', '=', 'roles.id')
            ->where('roles.slug', '=', 'Administrator');
    }

    public function isNotAdmin(): self
    {
        return $this
            ->addSelect([
                'roles.id as role_id',
                'roles.name',
                'roles.slug',
            ])
            ->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
            ->leftJoin('roles', 'role_users.role_id', '=', 'roles.id')
            ->where(function ($query) {
                $query->where('roles.slug', '!=', 'Administrator')
                    ->orWhereNull('roles.slug');
            });
    }
}
