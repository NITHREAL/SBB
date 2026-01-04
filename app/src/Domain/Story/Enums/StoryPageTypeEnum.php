<?php

namespace Domain\Story\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self simple()
 * @method static self action()
 * @method static self product()
 * @method static self news()
 * @method static self int_page()
 * @method static self int_page_mob()
 * @method static self catalog()
 * @method static self farmer()
 * @method static self chat()
 * @method static self ext_link()
 */
class StoryPageTypeEnum extends Enum
{
    protected static function labels()
    {
        return [
            'simple'        => 'Простой', // Кнопка не рисуется
            'action'        => 'Акция',   // todo slug
            'product'       => 'Продукт', // slug
            'news'          => 'Новость', // todo slug/id
            'int_page'      => 'Внутренняя страница сайта', // Ссылка
            'int_page_mob'  => 'Внутренняя страница МП', // todo Пока не понятно что хранить
            'catalog'       => 'Страница каталога', // Относительная ссылка /catalog/myaso/ptitsa
            'farmer'        => 'Страница фермера',  // slug
            'chat'          => 'Чат', // todo
            'ext_link'      => 'Внешняя ссылка', // Любой абсолютный url
        ];
    }
}
