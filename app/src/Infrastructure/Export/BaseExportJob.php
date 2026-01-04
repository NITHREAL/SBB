<?php

namespace Infrastructure\Export;

use Domain\User\Models\User;
use Illuminate\Foundation\Bootstrap\SetRequestForConsole;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Infrastructure\Notifications\Admin\ExportJobCompletedNotification;

abstract class BaseExportJob
{

    public array $filter = [];
    public ?User $user;
    public string $notificationTitle;

    public int $timeout = 1800;

    protected int $chunk = 1000;

    private string $fileExtension = 'csv';

    public function __construct($filter = [], ?User $user = null, $attr = [])
    {
        $this->filter = is_array($filter) ? $filter : [$filter];
        $this->user = $user;

        $this->notificationTitle = $attr['title'] ?? 'Export complete';
    }

    public function handle(): void
    {
        $this->initRequest();

        $csvFile = $this->getStorageFilePath();;

        Log::info('CSV FILE PATH: ' . $csvFile);

        $h = fopen($csvFile, 'w');
        //
        fputcsv($h, $this->headings(), "\t");
        $this->collection($h);
        fclose($h);

        $path = Storage::drive('public')->putFileAs('exports', new File($csvFile), basename($csvFile));
        $url = Storage::url($path);

        unlink($csvFile);

        $notification = new ExportJobCompletedNotification($this->notificationTitle);
        $notification->downloadUrl = $url;

        $this->user->notify($notification);
    }


    protected function initRequest()
    {
        if (!app()->runningInConsole()) {
            return;
        }

        (new SetRequestForConsole())->bootstrap(app());

        $request = request();
        $request->query->replace($this->filter);
    }

    protected function getStorageFilePath(): string
    {
        $path = tempnam(
            storage_path('app/public/laravel-excel'),
            sprintf('%s_%s', 'export', now()->format('Y-m-d_H:i:s')),
        );

        return sprintf(
            '%s.%s',
            $path,
            $this->fileExtension,
        );
    }

    abstract public function collection($resource): void;

    abstract public function headings(): array;

    abstract public function map($row): array;
}
