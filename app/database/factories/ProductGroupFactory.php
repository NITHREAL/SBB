<?php

namespace Database\Factories;

use Domain\ProductGroup\Models\ProductGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductGroupFactory extends Factory
{
    protected $model = ProductGroup::class;

    public function definition(): array
    {
        return [
            'audience_id'=> null,
            'story_id'=> null,
            'background_image_id'=> null,
            'system_id'=> $this->faker->uuid(),
            'active' => true,
            'title'=> Str::random(10),
            'slug'=> Str::random(10),
            'sort'=> $this->faker->numberBetween(400, 600),
            'site' => true,
            'mobile' => false,
        ];
    }
}
