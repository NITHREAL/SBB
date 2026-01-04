<?php

namespace Database\Seeders;

use Domain\Faq\Helpers\FaqCategoryHelper;
use Domain\Faq\Models\FaqCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FavoriteCategoriesFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqCategoryData = [
            'title'         => 'Любимые категории',
            'slug'          => FaqCategoryHelper::FAVORITE_CATEGORIES_FAQ_SLUG,
            'sort'          => 500,
            'active'        => true,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        $faqData = [
            [
                'title'         => 'Зачем выбирать любимые категории?',
                'text'          => 'Потому что надо!',
                'sort'          => 10,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'Можно ли изменить любимые категории?',
                'text'          => 'Нельзя! Только раз в месяц можно выбрать',
                'sort'          => 30,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'Что будет если не выбирать любимые категории?',
                'text'          => 'Наверное ничего страшного. Но точно будет много текста для того, чтобы посмотреть как будет выглядеть',
                'sort'          => 40,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        $faqCategory = FaqCategory::query()
            ->firstOrCreate(
                [
                    'slug' => FaqCategoryHelper::FAVORITE_CATEGORIES_FAQ_SLUG,
                ],
                $faqCategoryData,
            );

        foreach ($faqData as &$faqDataItem) {
            $faqDataItem['slug'] = Str::slug(Arr::get($faqDataItem, 'title'));
            $faqDataItem['active'] = true;
        }

        $faqCategory->questions()->createMany($faqData);
    }
}
