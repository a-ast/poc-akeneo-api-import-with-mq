<?php

namespace App\Command;

use App\Publisher\AmqpMessagePublisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    /**
     * @var \App\Publisher\AmqpMessagePublisher
     */
    private $publisher;

    public function __construct(AmqpMessagePublisher $publisher)
    {
        $this->publisher = $publisher;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('poc:product:publish')
            ->setDescription('Publish products to RabbitMQ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @todo: callback

        $this->publisher->execute();
    }
}
