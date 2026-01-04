<?php

namespace Domain\Exchange\Traits;

use Domain\Exchange\Helpers\CdnUploadedFile;
use Domain\Exchange\Helpers\UploadedFile;
use Domain\Image\Models\Attachment;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Orchid\Attachment\File;
use Orchid\Attachment\Models\Attachment as OrchidAttachment;

trait UploadFile
{
    private const WEBP_MIME = 'image/webp';

    private const WEBP_EXT = 'webp';

    protected function updateOrUpload(Request $request, Model $model, string $key, bool $detaching = false): void
    {
        if (Arr::isAssoc($request->get($key, []))) {
            $uploads = [$request->uploadedFile($key)];
        } else {
            $uploads = $request->uploadedFiles($key);
        }

        if ($uploads) {
            $this->upload($model, $uploads, $detaching);
        } else {
            $this->updateAttachment($request, $key);
        }
    }

    protected function updateAttachment(Request $request, string $key): void
    {
        $fileInfos = $request->get($key);

        if ($fileInfos) {
            foreach ($fileInfos as $fileInfo) {
                $disk = 'public';
                if ($originalName = Arr::get($fileInfo, 'nameonsdn')) {
                    $disk = 'cdn';
                    $originalName = $this->loadOnCdn($originalName);
                    $mime = self::WEBP_MIME;
                    $extension = self::WEBP_EXT;
                } else {
                    $originalName = $fileInfo['filename'] . '.' . $fileInfo['extension'];
                    $mime = $fileInfo['mime'];
                    $extension = $fileInfo['extension'];
                }

                Attachment::query()
                    ->where('system_id', $fileInfo['system_id'])
                    ->update([
                        'active' => $fileInfo['active'],
                        'main'   => $fileInfo['main'],
                        'mime'   => $mime,
                        'extension'   => $extension,
                        'disk'   => $disk,
                        'original_name' => $originalName,
                    ]);
            }
        }
    }

    private function loadOnCdn(string $originalName): string
    {
        ini_set('memory_limit', '2024M');

        try {
            $disk = Storage::disk('s3');
            $newFileName = pathinfo($originalName, PATHINFO_FILENAME) . '.webp';

            if ($disk->exists($newFileName) === false && $originalFile = $disk->get($originalName)) {
                $disk->put(
                    $newFileName,
                    $this->convert($originalFile)
                );

                if ($disk->exists($newFileName) === false) {
                    throw new Exception('Отсутствует изображение после загрузки на CDN');
                }
            }

            return $disk->url($newFileName);
        } catch (Exception $e) {
            Log::error('Ошибка при обработке изображения с CDN вызове функции put: ' . $e->getMessage());
        }

        return $originalName;
    }

    private function convert(string $file): Image
    {
        return Image::make($file)
            ->encode('webp');
    }

    protected function upload(Model $model, array $uploadedFiles, bool $detaching = true)
    {
        $mainAttachment = null;

        $attachment = collect($uploadedFiles)
            ->flatten()
            ->map(function (UploadedFile $file) use (&$mainAttachment) {
                $model = $this->createModel($file);

                if ($file->getIsMain()) {
                    $mainAttachment = $model;
                }

                return $model;
            });

        $ids = $attachment->pluck('id')->toArray();

        $model->attachment()->sync($ids, $detaching);

        if ($mainAttachment) {
            $this->makeAsMain($mainAttachment, $model);
        }

        return $attachment;
    }

    private function createModel(UploadedFile $file)
    {
        $model = resolve(File::class, [
            'file'  => $file,
            'disk'  => ($file instanceof CdnUploadedFile) ? 'cdn' : 'public'
        ])->load();

        if ($file instanceof CdnUploadedFile) {
            $originalName = $this->loadOnCdn($file->getUrlCdn());
            $mime = self::WEBP_MIME;
            $extension = self::WEBP_EXT;
        } else {
            $originalName = $file->getClientOriginalName();
            $mime = $file->getMimeType();
            $extension = $file->getExtension();
        }

        $model->fill([
            'active'        => $file->getActive(),
            'main'          => $file->getIsMain(),
            'mime'          => $mime,
            'extension'     => $extension,
            'original_name' => $originalName,
        ])->save();

        return $model;
    }

    private function makeAsMain(OrchidAttachment|Attachment $attachment, $model): void
    {
        $attachment->update(['main' => true]);

        $model->load('attachment');
        $ids = $model->attachment
            ->where('id', '!=', $attachment->id)
            ->pluck('id');

        Attachment::query()
            ->whereIn('id', $ids)
            ->update(['main' => false]);
    }
}
