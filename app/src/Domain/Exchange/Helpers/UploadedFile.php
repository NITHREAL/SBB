<?php

namespace Domain\Exchange\Helpers;

use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class UploadedFile extends \Illuminate\Http\UploadedFile
{
    /**
     * @var string|null - 1C ID
     */
    private ?string $id = null;

    private ?string $urlCdn = null;

    /**
     * @var bool
     */
    private bool $active = false;

    /**
     * @var bool - Determine file is main for related item
     */
    private bool $isMain = false;

    public function __construct(string $base64, string $filename, string $mime)
    {
        $tempName = '/tmp/' . md5($filename . microtime());
        $file = base64_decode($base64);

        if (!$file) {
            throw new UploadException('Can not decode base64');
        }

        $filesize = file_put_contents($tempName, $file);

        if (!$filesize) {
            throw new UploadException('Can not store file in temporary dir');
        }

        parent::__construct($tempName, $filename, $mime);
    }

    public function getClientOriginalName(): string
    {
        if ($this->urlCdn) {
            return $this->urlCdn;
        }

        return parent::getClientOriginalName();
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getIsMain(): bool
    {
        return $this->isMain;
    }

    public function setId(string $id): UploadedFile
    {
        $this->id = $id;

        return $this;
    }

    public function setActive(bool $active): UploadedFile
    {
        $this->active = $active;

        return $this;
    }

    public function setIsMain(bool $isMain): UploadedFile
    {
        $this->isMain = $isMain;

        return $this;
    }


    public function getUrlCdn(): ?string
    {
        return $this->urlCdn;
    }

    public function setUrlCdn(?string $urlCdn): self
    {
        $this->urlCdn = $urlCdn;

        return $this;
    }

}
