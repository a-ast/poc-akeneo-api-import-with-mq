<?php

namespace App\Publisher;

use App\Model\DataProviderInterface;
use App\Model\PimEntityDispatcherInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AmqpMessagePublisher
{
    /**
     * @var DataProviderInterface
     */
    private $dataProvider;

    /**
     * @var PimEntityDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \PhpAmqpLib\Connection\AbstractConnection
     */
    private $connection;

    const BATCH_SIZE = 100;

    public function __construct(DataProviderInterface $dataProvider,
        PimEntityDispatcherInterface $dispatcher,
        AbstractConnection $connection)
    {
        $this->dataProvider = $dataProvider;
        $this->dispatcher = $dispatcher;
        $this->connection = $connection;
    }

    public function execute()
    {
        $channel = $this->connection->channel();

        $channel->queue_declare('create_product_model_queue', false, true, false, false);
        $channel->queue_declare('create_product_queue', false, true, false, false);

        $messages = [];

        foreach ($this->dataProvider->getData() as $product) {

            $queueName = $this->dispatcher->getDestination($product);
            $messages[$queueName][] = json_encode($product->toStandardFormat());

            $this->publishWhenBatchReady($channel, $messages);
        }

        // publish remaining messages
        $this->publishWhenBatchReady($channel, $messages);

        $this->connection->close();
    }

    private function publishWhenBatchReady(AMQPChannel $channel, array &$messagePool)
    {
        foreach ($messagePool as $queueName => $messages) {

            if (count($messages) < self::BATCH_SIZE) {
                continue;
            }

            $messagesToPublish = array_slice($messages, 0, self::BATCH_SIZE);

            $message = new AMQPMessage(implode(PHP_EOL, $messagesToPublish));
            $channel->basic_publish($message, '', $queueName);

            // add rest of the message to the pool
            $messagePool[$queueName] = array_slice($messages, self::BATCH_SIZE);
        }
    }
}
