<?php

namespace Domain\Product\Export;

use Domain\Product\Models\ExpectedProduct;
use Domain\Product\Services\ExpectedProduct\ExpectedProductService;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class ExpectedProductsExport implements FromCollection, Responsable, WithHeadings, WithMapping
{
    use Exportable;

    private string $writerType = Excel::CSV;
    private string $fileName;

    private array $headers = [
        'Content-Type' => 'text/csv',
    ];

    private array $filters;

    private ExpectedProductService $expectedProductService;

    public function __construct(array $filters)
    {
        $this->filters = $filters;

        $this->setFilename();

        $this->expectedProductService = app()->make(ExpectedProductService::class);
    }

    public function collection(): Collection
    {
        return ExpectedProduct::query()
            ->with(['product', 'user'])
            ->when($this->filters, function ($query) {
                if ($users = Arr::get($this->filters, 'users', [])) {
                    $query->whereIn('user_id', $users);
                }

                if ($products = Arr::get($this->filters, 'products', [])) {
                    $query->whereIn('product_id', $products);
                }
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            __('admin.expected_product.table.product'),
            __('admin.expected_product.table.product_id'),
            __('admin.expected_product.table.user'),
            __('admin.expected_product.table.user_id'),
            __('admin.expected_product.table.created_at'),
        ];
    }

    public function map(mixed $row): array
    {
        return [
            $row->product->title,
            $row->product_id,
            $row->user->first_name,
            $row->user_id,
            $row->created_at
        ];
    }

    private function setFilename(): void
    {
        $this->fileName = sprintf(
            '%s_от_%s.%s',
            'Список товаров "Привезти ещё"',
            now()->format('d.m.Y'),
            'csv',
        );
    }
}
