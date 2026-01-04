<?php

namespace Database\Seeders;

use Domain\Faq\Models\Faq;
use Domain\Faq\Models\FaqCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BonusesFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Schema::hasTable('faq_categories') || !Schema::hasTable('faq')) {
            $this->command->warn('Таблицы faq_categories или faq не существуют. Пропуск выполнения сидера.');
            return;
        }

        DB::transaction(function () {
            $category = FaqCategory::create([
                'title' => 'Бонусы',
                'slug' => 'bonuses',
                'active' => true,
                'sort' => 1,
                'protected' => true,
            ]);

            $faqData = [
                [
                    'title' => 'Как получить кешбек бонусами?',
                    'text' => 'Вы можете получить кешбек бонусами в зависимости от суммы, которую вы тратите. Чем больше вы тратите, тем выше уровень кешбека.',
                    'slug' => 'how-to-get-cashback-with-bonuses',
                    'active' => true,
                    'sort' => 1,
                ],
                [
                    'title' => 'Как получать больше кешбека и скидок?',
                    'text' => 'Для получения большего количества кешбека и скидок, участвуйте в наших акциях и специальных предложениях. Следите за обновлениями на сайте и в приложении.',
                    'slug' => 'how-to-get-more-cashback-and-discounts',
                    'active' => true,
                    'sort' => 2,
                ],
                [
                    'title' => 'Как оплачивать покупки бонусами?',
                    'text' => 'Для оплаты покупок бонусами выберите опцию "Оплатить бонусами" на этапе оформления заказа. Укажите количество бонусов, которые хотите списать.',
                    'slug' => 'how-to-pay-for-purchases-with-bonuses',
                    'active' => true,
                    'sort' => 3,
                ],
                [
                    'title' => 'Сколько бонусов можно списать?',
                    'text' => 'Максимальное количество бонусов, которые можно списать, зависит от текущего баланса и условий акции. Обычно это до 50% от стоимости покупки.',
                    'slug' => 'how-many-bonuses-can-be-deducted',
                    'active' => true,
                    'sort' => 4,
                ],
            ];

            foreach ($faqData as $faq) {
                Faq::create(array_merge($faq, ['faq_category_id' => $category->id]));
            }
        });
    }
}

