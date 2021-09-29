<?php

namespace App\Command;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UploadFileCommand extends Command
{
    private $localFilesystem;
    private $mediaFilesystem;

    protected static $defaultName = 'app:upload-file';

    public function __construct(
        FilesystemInterface $localFilesystem,
        FilesystemInterface $mediaFilesystem
    ) {
        $this->mediaFilesystem = $mediaFilesystem;
        $this->localFilesystem = $localFilesystem;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = "testfile_400mb";

        $remoteTransferSuccess = $this->mediaFilesystem->write(
            $filename,
            $this->localFilesystem->readStream($filename),
            ['visibility' => 'public'] // Bucket has to be set to public
        );

        if ($remoteTransferSuccess) {
            $output->writeln('Remote transfer success!');
        } else {
            $output->writeln('Remote NOT transfer success!');
        }

        $output->writeln('Complete!');

        return Command::SUCCESS;
    }
}
