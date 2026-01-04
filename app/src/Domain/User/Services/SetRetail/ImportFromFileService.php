<?php

namespace Domain\User\Services\SetRetail;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Infrastructure\Helpers\PhoneFormatterHelper;

class ImportFromFileService
{
    public function loadFile(string $file): array
    {
        $users = [];
        $bonuses = [];

        $h = fopen($file, 'r');

        while (($row = fgetcsv($h, null, ';')) !== false) {
            $phone = Arr::get($row, 1);

            if ($this->isPhoneCorrect($phone) === false) {
                continue;
            }

            $users[] = $this->getUserData($row,$phone);

            if (count($users) >= 100) {
                DB::table('users')->insertOrIgnore($users);

                $users = [];
            }

            $bonuses[PhoneFormatterHelper::unformat($phone)] = Arr::get($row, 8);
        }

        DB::table('users')->insertOrIgnore($users);

        return $bonuses;
    }

    private function getUserData(array $row, string $phone): array
    {
        $birthday = $this->getBirthday($row);
        [$first_name, $last_name] = $this->getNameData($row);
        $email = Arr::get($row, 2);

        return [
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'email'         => $email,
            'phone'         => PhoneFormatterHelper::unformat($phone),
            'birthday'      => $birthday,
        ];
    }

    private function getBirthday(array $row): ?string
    {
        $birthday = trim(Arr::get($row, 4));

        if (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $birthday)) {
            $birthday = Carbon::createFromFormat('d.m.Y', trim($row[4]));
        } else {
            $birthday = null;
        }

        return $birthday;
    }

    private function getNameData(array $row): array
    {
        $data = explode(
            ' ',
            preg_replace('/ +/', ' ', Arr::get($row, 3)),
            2
        );

        return [
            'first_name'    => Arr::get($data, 0, ''),
            'last_name'     => Arr::get($data, 1, ''),
        ];
    }

    private function isPhoneCorrect(string $phone): bool
    {
        return preg_match('/^7\d{10}$/', $phone);
    }
}
