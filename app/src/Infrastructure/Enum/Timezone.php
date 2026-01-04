<?php

namespace Infrastructure\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self kaliningrad()
 * @method static self kirov()
 * @method static self moscow()
 * @method static self simferopol()
 * @method static self astrakhan()
 * @method static self volgograd()
 * @method static self samara()
 * @method static self saratov()
 * @method static self ulyanovsk()
 * @method static self yekaterinburg()
 * @method static self omsk()
 * @method static self barnaul()
 * @method static self krasnoyarsk()
 * @method static self novokuznetsk()
 * @method static self novosibirsk()
 * @method static self tomsk()
 * @method static self irkutsk()
 * @method static self chita()
 * @method static self khandyga()
 * @method static self yakutsk()
 * @method static self vladivostok()
 * @method static self ust_nera()
 * @method static self magadan()
 * @method static self sakhalin()
 * @method static self srednekolymsk()
 * @method static self anadyr()
 * @method static self kamchatka()
 */
class Timezone extends Enum
{
    protected static function labels(): array
    {
        return [
            'kaliningrad'   => '(UTC +02:00) Калининград',
            'kirov'         => '(UTC +03:00) Киров',
            'moscow'        => '(UTC +03:00) Москва',
            'simferopol'    => '(UTC +03:00) Симферополь',
            'astrakhan'     => '(UTC +04:00) Астрахань',
            'volgograd'     => '(UTC +04:00) Волгоград',
            'samara'        => '(UTC +04:00) Самара',
            'saratov'       => '(UTC +04:00) Саратов',
            'ulyanovsk'     => '(UTC +04:00) Ульяновск',
            'yekaterinburg' => '(UTC +05:00) Екатеринбург',
            'omsk'          => '(UTC +06:00) Омск',
            'barnaul'       => '(UTC +07:00) Барнаул',
            'krasnoyarsk'   => '(UTC +07:00) Красноярск',
            'novokuznetsk'  => '(UTC +07:00) Новокузнецк',
            'novosibirsk'   => '(UTC +07:00) Новосибирск',
            'tomsk'         => '(UTC +07:00) Томск',
            'irkutsk'       => '(UTC +08:00) Иркутск',
            'chita'         => '(UTC +09:00) Чита',
            'khandyga'      => '(UTC +09:00) Хандыга',
            'yakutsk'       => '(UTC +09:00) Якутск',
            'vladivostok'   => '(UTC +10:00) Владивосток',
            'ust_nera'      => '(UTC +10:00) Усть-Нера',
            'magadan'       => '(UTC +11:00) Магадан',
            'sakhalin'      => '(UTC +11:00) Южно-Сахалинск',
            'srednekolymsk' => '(UTC +11:00) Среднеколымск',
            'anadyr'        => '(UTC +12:00) Анадырь',
            'kamchatka'     => '(UTC +12:00) Петропавловск-Камчатский',
        ];
    }

    protected static function values(): array
    {
        return [
            'kaliningrad'   => 'Europe/Kaliningrad',
            'kirov'         => 'Europe/Kirov',
            'moscow'        => 'Europe/Moscow',
            'simferopol'    => 'Europe/Simferopol',
            'astrakhan'     => 'Europe/Astrakhan',
            'volgograd'     => 'Europe/Volgograd',
            'samara'        => 'Europe/Samara',
            'saratov'       => 'Europe/Saratov',
            'ulyanovsk'     => 'Europe/Ulyanovsk',
            'yekaterinburg' => 'Asia/Yekaterinburg',
            'omsk'          => 'Asia/Omsk',
            'barnaul'       => 'Asia/Barnaul',
            'krasnoyarsk'   => 'Asia/Krasnoyarsk',
            'novokuznetsk'  => 'Asia/Novokuznetsk',
            'novosibirsk'   => 'Asia/Novosibirsk',
            'tomsk'         => 'Asia/Tomsk',
            'irkutsk'       => 'Asia/Irkutsk',
            'chita'         => 'Asia/Chita',
            'khandyga'      => 'Asia/Khandyga',
            'yakutsk'       => 'Asia/Yakutsk',
            'vladivostok'   => 'Asia/Vladivostok',
            'ust_nera'      => 'Asia/Ust-Nera',
            'magadan'       => 'Asia/Magadan',
            'sakhalin'      => 'Asia/Sakhalin',
            'srednekolymsk' => 'Asia/Srednekolymsk',
            'anadyr'        => 'Asia/Anadyr',
            'kamchatka'     => 'Asia/Kamchatka'
        ];
    }
}
