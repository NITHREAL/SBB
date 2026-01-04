<?php

namespace Domain\Exchange\Helpers;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use const PATHINFO_EXTENSION;

class CdnUploadedFile extends UploadedFile
{
    public function __construct(string $path, string $filename, string $mime)
    {
        UploadedFile::__construct($path, $filename, $mime, UPLOAD_ERR_EXTENSION, true);
    }
    public function path()
    {
        return $this->getUrlCdn() ?? $this->getFilename();
    }

    public function getSize(): int|false
    {
        return 10;
    }

    public function getClientOriginalExtension(): string
    {
        return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
    }

}
