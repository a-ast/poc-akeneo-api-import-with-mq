<?php

namespace App\Command;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('poc:publish-product')
            ->setDescription('Publish product');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('create_product_queue', false, true, false, false);

        for($i = 0; $i < 1000; $i++) {

            $productData = $this->getProductData();
            $message = new AMQPMessage(json_encode($productData));

            $channel->basic_publish($message, '', 'create_product_queue');
            $output->writeln(
                sprintf('Product %d published', $i)
            );
        }
        $channel->close();
        $connection->close();

    }

    private function getProductData()
    {
        return [
            'family' => 'helmets',
            'identifier' => tempnam('', 'product-'),
        ];
    }

}
