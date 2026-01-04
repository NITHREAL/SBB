<?php

namespace Domain\Audience\Export;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class AudienceUsersFileExampleExport implements FromCollection, Responsable, WithHeadings, WithMapping
{
    use Exportable;

    private int $model;

    public function __construct(array $filters)
    {
        $this->model = intval(Arr::get($filters, 'model'));
    }

    private string $fileName = "AudienceUsers.csv";
    private string $writerType = Excel::CSV;
    private array $headers = [
        'Content-Type' => 'text/csv',
    ];

    public function collection(): Collection
    {
        return collect();
    }

    public function headings(): array
    {
        return [
            'ID пользователя',
        ];
    }

    public function map(mixed $row): array
    {
        return [];
    }
}
