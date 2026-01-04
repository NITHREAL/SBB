<?php

namespace Infrastructure\Services\Buyer;

use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\Buyer\Facades\BuyerToken;

abstract class BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'default';

    protected string $attributeCachePrefix = 'buyer';

    protected int $cacheTtl;

    protected string $token;

    public function __construct()
    {
        $this->cacheTtl = config('api.buyer.token_ttl');
        $this->token = BuyerToken::getValue();
    }

    public function setValue(string|array $value): void
    {
        $this->setCachedValue($value);
    }

    public function setDefaultValue(): void
    {
        $this->setCachedValue(
            $this->getDefaultValue()
        );
    }

    public function getValue(): string|array|null
    {
        $value = $this->getCachedValue();

        // Не выносил логику добавления в кэш дефолтного значения в отдельный метод init и конструктор чтобы
        // при инициализации сервисов в провайдере не выполнялась лишняя логика,
        // а выполнялась только по мере необходимости
        if (!$value) {
            $value = $this->getDefaultValue();

            // Для некоторых атрибутов корректное значение по умолчанию не предусмотрено
            if ($value) {
                $this->setCachedValue($value);
            }
        }

        return $value;
    }

    abstract protected function getDefaultValue(): string|array|null;

    protected function setCachedValue(string|array $value): void
    {
        Cache::put($this->getAttributeKey(), $value, $this->cacheTtl);
    }

    protected function getCachedValue(mixed $default = null): mixed
    {
        return Cache::get($this->getAttributeKey(), $default);
    }

    private function getAttributeKey(): string
    {
        return sprintf('%s_%s_%s', $this->attributeCachePrefix, static::ATTRIBUTE_CACHE_KEY, $this->token);
    }
}
