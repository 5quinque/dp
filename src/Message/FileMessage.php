<?php

namespace App\Message;

class FileMessage
{
    private $fileId;
    private $filePath;

    public function __construct(int $fileId, string $filePath)
    {
        $this->fileId = $fileId;
        $this->filePath = $filePath;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
