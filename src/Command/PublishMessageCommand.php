<?php

namespace App\Command;

use App\Message\FileMessage;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PublishMessageCommand extends Command
{
    protected static $defaultName = 'app:publish-message';
    private $bus;

    public function __construct(
        MessageBusInterface $bus
    ) {
        $this->bus = $bus;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new FileMessage(9999));

        $output->writeln('Complete!');

        return Command::SUCCESS;
    }
}
