<?php

namespace App\Message;

class FileMessage
{
    private $fileId;

    public function __construct(int $fileId)
    {
        $this->fileId = $fileId;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }
}
