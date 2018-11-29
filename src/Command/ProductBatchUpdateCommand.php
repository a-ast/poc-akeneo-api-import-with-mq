<?php

namespace App\Command;

use Exception;
use App\ProductUpdater\ApiProductBatchUpdater;
use App\ProductUpdater\Exceptions\MissingParentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductBatchUpdateCommand extends Command
{
    public const STATUS_ACKNOWLEDGEMENT = 0;

    public const STATUS_REJECT = 3;

    public const STATUS_REJECT_AND_REQUEUE = 4;

    public const STATUS_STOP = 1;

    /**
     * @var \App\ProductUpdater\ApiProductBatchUpdater
     */
    private $updater;

    /**
     */
    public function __construct(ApiProductBatchUpdater $updater)
    {
        $this->updater = $updater;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('poc:product:update')
            ->setDescription('Update products in PIM using messages data form MQ')
            ->addArgument('message', InputArgument::REQUIRED)
            ->addOption('type', 't', InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $json = base64_decode($input->getArgument('message'));
        $messageItems = $this->getMessageItems($json);

        $dataType = $input->getOption('type');

        try {

            $this->updater->update($messageItems, $dataType);

        } catch (MissingParentException $e) {

            return static::STATUS_REJECT_AND_REQUEUE;

        } catch(HttpException $e) {

            return static::STATUS_REJECT;

        } catch(Exception $e) {

            print $e->getMessage();

            return static::STATUS_STOP;
        }

        return static::STATUS_ACKNOWLEDGEMENT;
    }

    private function getMessageItems(string $json): array
    {
        $items = array_map(function($item) {
            return json_decode($item, true);
        }, explode(PHP_EOL, $json));

        return $items;
    }
}
