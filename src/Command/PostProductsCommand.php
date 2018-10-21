<?php

namespace App\Command;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;
use Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PostProductsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('poc:product:post')
            ->addArgument('event', InputArgument::REQUIRED)
            ->addOption('type', 't', InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $json = base64_decode($input->getArgument('event'));
        $messageItems = $this->getMessageItems($json);

        $clientBuilder = new \Akeneo\Pim\ApiClient\AkeneoPimClientBuilder('http://host.docker.internal:8080/');
        $client = $clientBuilder->buildAuthenticatedByPassword('1_3wdcmdz4fxgkgk480swwggwcogkwgksscgokkogk8ssowg88ks', '31f1il9m1k4k0ckw4w44gk80kggc4go0swggkw008g408kw4co', 'admin', 'admin');

        $dataType = $input->getOption('type');

        print $dataType;

        try {

            $upsertedResources = $this->getApi($client, $dataType)->upsertList($messageItems);

            foreach ($upsertedResources as $response) {

                // identifier|code, status_code, message, errors

                if (!in_array($response['status_code'], [201, 204])) {
                    print $response['message'];

                    // Exit 4 only for absence of parent, otherwise 3
                    exit(4);
                }

            }


        } catch (UnprocessableEntityHttpException $e) {

            exit(4);

        } catch(Exception $e) {

            exit(1); // Unexpected exception will cause consumer to stop consuming

        }
    }

    protected function getApi(AkeneoPimClientInterface $client, string $dataType): UpsertableResourceListInterface
    {
        if ('model' === $dataType) {
            return $client->getProductModelApi();
        }

        return $client->getProductApi();
    }

    private function getMessageItems(string $json): array
    {
        $items = array_map(function($item) {
            return json_decode($item, true);
        }, explode(PHP_EOL, $json));

        var_dump($items);

        return $items;
    }
}
