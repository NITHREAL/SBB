<?php

namespace Domain\Audience\Import;

use Domain\Audience\Models\Audience;
use Domain\Audience\Service\AudienceUsersService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Excel;

class AudienceUsersImport implements ToCollection, WithStartRow
{
    use Importable;

    private readonly AudienceUsersService $audienceUsersService;

    private Audience $audience;

    private string $writerType = Excel::CSV;

    private array $headers = [
        'Content-Type' => 'text/csv',
    ];


    public function __construct(Audience $audience)
    {
        $this->audience = $audience;
        $this->audienceUsersService = app()->make(AudienceUsersService::class);
    }

    /**
     * @throws \Exception
     */
    public function collection(Collection $collection): void
    {
        $data = $collection->filter(fn($item) => !empty($item->get(0)) || !empty($item->get(1)));

        $this->audienceUsersService->importUsersFromFile($this->audience, $data);
    }

    public function startRow(): int
    {
        return 2;
    }
}
