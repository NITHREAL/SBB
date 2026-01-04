<?php

namespace Domain\Product\Services\Favorite;

use Domain\Product\Models\Category;
use Domain\Product\Models\Favorite;
use Domain\Product\Models\Product;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FavoriteService
{
    public function getFavorite(): Favorite
    {
        return $this->firstOrCreateFavorite();
    }

    public function addProduct(int $productId): Favorite
    {
        $favorite = $this->getFavorite();
        $favorite->products()->attach($productId);

        $catalog = Category::query()->where(
            'system_id',
            Product::query()->find($productId)->categories()->pluck('parent_system_id')->first()
        )->first();

        Cache::forget(sprintf('%s_%s', 'categories_childs', $catalog->slug));


        return $favorite;
    }

    public function deleteProduct(int $productId): Favorite
    {
        $favorite = $this->getFavorite();
        $favorite->products()->detach($productId);

        return $favorite;
    }

    public function getToken(): ?string
    {
        $headerName = config("api.headers.favorite");

        return request()->header($headerName);
    }

    private function firstOrCreateFavorite(): Favorite
    {
        /** @var User $user */
        $user = Auth::user();
        $token = $this->getToken() ?? $this->generateToken();

        if ($user) {
            $favorite = $this->getByUser($user, $token);
        } else {
            $favorite = $this->getByToken($token);
        }

        return $favorite;
    }

    private function getByUser(User $user, ?string $token): Favorite
    {
        $favorite = Favorite::query()->where('user_id', $user->id)->first();
        $token = $token ?? $this->generateToken();

        if ($favorite) {
            if (empty($favorite->token)) {
                $favorite->token = $token;
                $favorite->save();
            }
        } else {
            $favorite = new Favorite();
            $favorite->token = $token;
            $favorite->user()->associate($user);
            $favorite->save();
        }

        return $favorite;
    }

    private function getByToken(string $token): Favorite
    {
        return Favorite::firstOrCreate(['token' => $token]);
    }

    private function generateToken(): string
    {
        return Str::uuid();
    }
}
