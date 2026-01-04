<?php

namespace Domain\Exchange\Traits;

use Domain\Exchange\Helpers\CdnUploadedFile;
use Domain\Exchange\Helpers\UploadedFile;
use Domain\Exchange\Requests\ItemRequest;
use Domain\Exchange\Rules\Base64;
use Illuminate\Support\Arr;

trait Fileable
{
    protected ?array $fileable = null;

    public function fileRules(string $key): array
    {
        return [
            $key . '.active' => "required_with:${key}|boolean",
            $key . '.system_id' => "required_with:${key}|string",
            $key . '.main' => "required_with:${key}|boolean",
            $key . '.filename' => "required_with:${key}",
            $key . '.extension' => "required_with:${key}|string",
            $key . '.mime' => "required_with:${key}|string|regex:/\w+\/[-+.\w]+/i",
            $key . '.file' => [new Base64(), 'nullable'],
            $key . '.nameonsdn' => "required_with:${key}|string|nullable",
        ];
    }

    /**
     * @param string $key
     * @return UploadedFile[]
     */
    public function uploadedFiles(string $key): array
    {
        /** @var $this ItemRequest */
        $files = array_map(function ($fileData) {
            return $this->makeFromFileData($fileData);
        }, $this->get($key, []));

        return array_filter($files);
    }

    /**
     * @param string $key
     * @return UploadedFile|null
     */
    public function uploadedFile(string $key): UploadedFile|null
    {
        /** @var $this ItemRequest */
        $fileData = $this->get($key, []);
        $uploaded = Arr::get($fileData, 'file');

        return $uploaded ? $this->makeFromFileData($fileData) : null;
    }

    /**
     * @param array $data
     * @return UploadedFile|null
     */
    private function makeFromFileData(array $data): UploadedFile|null
    {
        [
            'active' => $active,
            'system_id' => $id,
            'main' => $main,
            'filename' => $name,
            'extension' => $ext,
            'mime' => $mime
        ] = $data;

        $uploadedFile = null;

        $file = $data['file'] ?? null;
        if ($nameonsdn = $data['nameonsdn'] ?? null) {
            $uploadedFile = new CdnUploadedFile($nameonsdn, "${name}.${ext}", $mime);
            $uploadedFile->setUrlCdn($nameonsdn);
        } elseif ($file) {
            $uploadedFile = new UploadedFile($file, "${name}.${ext}", $mime);
        }

        $uploadedFile
            ?->setId($id)
            ->setActive($active)
            ->setIsMain($main);

        return $uploadedFile;
    }
}
