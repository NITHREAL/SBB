<?php

declare(strict_types=1);

namespace App\Orchid\Presenters;

use Infrastructure\Helpers\PhoneFormatterHelper;
use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class UserPresenter extends Presenter implements Searchable, Personable
{

    private const ANONYMOUS = 'Аноним';
    private const DEFAULT_AVATAR_URL = "https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp";
    /**
     * @return string
     */
    public function label(): string
    {
        return 'Users';
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $names = array_filter([ $this->entity->last_name, $this->entity->first_name, $this->entity->middle_name]);

        if (empty($names)) {
            return self::ANONYMOUS;
        }

        return implode(' ', $names);
    }

    /**
     * @return string
     */
    public function subTitle(): string
    {
        $roles = $this->entity->roles->pluck('name')->implode(' / ');

        return empty($roles)
            ? ''
            : $roles;
    }

    public function phoneNumber(): string|null
    {
        $phone = $this->entity->phone;

        if ($phone) {
            $phone = PhoneFormatterHelper::format($phone);
        }

        return $phone;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('platform.systems.users.edit', $this->entity);
    }

    /**
     * @return string
     */
    public function image(): string
    {
        if ($this->entity->email) {
            $hash = md5(strtolower(trim($this->entity->email)));
            return "https://www.gravatar.com/avatar/$hash?d=mp";
        }

        return self::DEFAULT_AVATAR_URL;
    }

    /**
     * The number of models to return for show compact search result.
     *
     * @return int
     */
    public function perSearchShow(): int
    {
        return 3;
    }

    /**
     * @param string|null $query
     *
     * @return Builder
     */
    public function searchQuery(string $query = null): Builder
    {
        return $this->entity->search($query);
    }
}
