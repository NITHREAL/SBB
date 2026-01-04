<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\Cdn\CdnFilesystemAdapter;

class CdnServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('cdn', function ($app, array $config) {
            $adapter = new CdnFilesystemAdapter();

            return new FilesystemAdapter(
                new Filesystem($adapter, $config)
            );
        });
    }
}
