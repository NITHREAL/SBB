<?php

namespace Domain\Promocode\Export\Excel;

use Domain\Promocode\Models\Promocode;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class PromoExport implements FromCollection, Responsable, WithHeadings, WithMapping
{
    use Exportable;

    private string $fileName;
    private string $writerType = Excel::XLSX;
    private array $headers = [
        'Content-Type' => 'text/xlsx',
    ];

    public function __construct()
    {
        $this->fileName = sprintf(
            '%s%s%s',
            'Отчет_использования_промокодов_от_',
            now()->format('d.m.Y'),
            '.xlsx',
        );
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Promocode::query()
            ->whereHas('orders', function ($query) {
                $query->whereNotCanceled();
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'used_count' => 'Кол-во использований',
            'total_discount' => 'Общая сумма скидки, руб',
            'free_delivery' => 'Код на бесплатную доставку'
        ];
    }

    /**
     * @param Promocode $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->code,
            $row->usedCount,
            $row->totalDiscount,
            $row->free_delivery ? 'Да' : 'Нет'
        ];
    }
}
