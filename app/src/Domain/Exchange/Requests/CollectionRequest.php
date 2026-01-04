<?php

namespace Domain\Exchange\Requests;

use Closure;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

abstract class CollectionRequest extends FormRequest
{
    /**
     * @var ItemRequest[]
     */
    private array $items = [];

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $this->collectItems();
    }

    /**
     * @throws Exception
     */
    private function collectItems(): void
    {
        $this->logMessage();

        $params = $this->all();

        if (Arr::isAssoc($params)) {
            throw new Exception('Can not collect associative array', 400);
        }

        $this->items = array_map(function($item) {
            $class = $this->getItemRequestClass();

            /** @var ItemRequest $itemRequest */
            $itemRequest = new $class();
            $itemRequest->replace($item);
            $itemRequest->setContainer(app());
            $itemRequest->setRedirector(app()->make(Redirector::class));
            $itemRequest->setValidator($itemRequest->getValidatorInstance());

            return $itemRequest;
        }, $params);
    }

    protected function logMessage(): void
    {
        $params = $this->all();
        Log::info($_SERVER['REQUEST_URI'] . ' // '
            . json_encode(
                collect($params)->map(fn($val) => Arr::except($val, 'images'))->toArray()
            )
        );
    }

    public function map(Closure $callback): array
    {
        return array_map($callback, $this->items);
    }

    public function filter(Closure $callback): array
    {
        return array_filter($this->items, $callback);
    }

    public function toArray(): array
    {
        return $this->map(function($item) {
            return $item->all();
        });
    }

    abstract public function getItemRequestClass(): string;
}
