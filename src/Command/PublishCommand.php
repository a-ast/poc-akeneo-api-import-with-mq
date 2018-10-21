<?php

namespace App\Command;

use App\Model\ProductModel;
use App\ProductBuilder\FakeProductProvider;
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
            ->setName('poc:product:publish')
            ->setDescription('Publish products to RabbitMQ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('create_product_queue', false, true, false, false);
        $channel->queue_declare('create_product_model_queue', false, true, false, false);

        $provider = new FakeProductProvider();

        $i = 1;

        foreach ($provider->getProductModelsAndProducts() as $product) {

            $message = new AMQPMessage(json_encode($product->toArray()));

            $queueName = $product instanceof ProductModel ? 'create_product_model_queue' : 'create_product_queue';

            $channel->basic_publish($message, '', $queueName);

            $output->writeln(
                sprintf('Product %d published', $i++)
            );
        }

        $channel->close();
        $connection->close();

    }
}
