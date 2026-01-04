<?php

namespace Domain\Audience\Export;

use Domain\Audience\Models\AudienceList;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Infrastructure\Export\BaseExportJob;

class AudienceUsersExport extends BaseExportJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200;

    public function collection($resource): void
    {
        $audienceId = $this->filter['id'];

        fputs($resource, "\xEF\xBB\xBF");

        AudienceList::where('audience_id', $audienceId)
            ->with('user', function ($query) {
                $query->withTrashed();
            })
            ->chunk($this->chunk, function ($rows) use ($resource) {
                /** @var Collection $rows */
                $rows->each(function ($row) use ($resource) {
                    /** @var AudienceList $row */
                    $data = $this->map($row);

                    $data = array_map(function ($value) {
                        return mb_convert_encoding($value, 'UTF-8', 'auto');
                    }, $data);

                    fputcsv($resource, $data, "\t");
                });
            });
    }

    public function headings(): array
    {
        return [
            'ID пользователя',
            'Номер телефона пользователя',
            'Имя пользователя',
            'Дата проникновения в аудиторию',
            'Дата исчезновения из аудитории',
            'Номер карты пользователя',
        ];
    }

    public function map($row): array
    {
        /** @var AudienceList $row */
        if (!$row->user) {
            return [];
        }
        return [
            $row->user->id,
            $row->user->phone,
            $row->user->full_name,
            $row->created_at,
            $row->deleted_at,
            $row->user->getCartNumber(),
        ];
    }
}
