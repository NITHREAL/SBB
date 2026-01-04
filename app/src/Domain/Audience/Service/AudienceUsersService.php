<?php

namespace Domain\Audience\Service;

use Domain\Audience\Models\Audience;
use Domain\Audience\Models\AudienceList;
use Domain\User\Models\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AudienceUsersService
{
    /**
     * @throws Exception
     */
    public function importUsersFromFile(Audience $audience, Collection $data): void
    {
        $usersByPhones = $this->getUsersByPhone(
            $data->filter(fn($item) => empty(Arr::get($item, 0)))->toArray()
        );

        $userIds = array_merge(
            $data->map(fn($item) => (int) Arr::get($item, 0))->toArray(),
            $usersByPhones->pluck('id')->toArray(),
        );

        $message = 'Ошибка при загрузке файла с пользователями аудиторий';

        $this->updateAudienceUsers($audience, $userIds, $message);
    }

    /**
     * @throws Exception
     */
    public function updateUsersFromSelect(Audience $audience, $userIds): int
    {
        $message = 'Ошибка при загрузке пользователей аудиторий через поле селект';

        return $this->updateAudienceUsers($audience, $userIds, $message);
    }

    public function removeUserFromAudiences(User $user): void
    {
        $audienceLists = AudienceList::query()->where('user_id', $user->id)->get();
        $audiences = Audience::query()->whereIn('id', $audienceLists->pluck('audience_id'))->get();

        AudienceList::destroy($audienceLists->pluck('id'));

        foreach ($audienceLists as $audienceList) {
            $audience = $audiences->where('id', $audienceList->audience_id)->first();

            $userIds = $audience->users()->pluck('users.id')->toArray();
            $audience->update(['users_count' => count($userIds)]);
        }
    }

    private function updateAudienceUsers(
        Audience $audience,
        array $userIds,
        string $message,
    ): ?int {
        $existedUserIds = User::query()->whereIn('id', $userIds)->pluck('id')->toArray();

        $usersCount = count($existedUserIds);

        try {
            DB::transaction(function () use ($audience, $existedUserIds, $usersCount) {
                $audience->users()->sync($existedUserIds);
                $audience->update(['users_count' => $usersCount]);
            });
        } catch (Exception $e) {
            Log::channel('debug')->error($message, [
                'audience_id' => $audience->id,
                'error_message' => $e->getMessage()
            ]);

            throw $e;
        }

        return $usersCount;
    }

    private function getUsersByPhone(array $userPhones): Collection
    {
        return User::query()->whereIn('phone', $userPhones)->get();
    }
}
