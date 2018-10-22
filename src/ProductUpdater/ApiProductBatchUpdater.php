<?php

namespace App\ProductUpdater;

use App\ProductUpdater\Exceptions\MissingParentException;
use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\Operation\UpsertableResourceListInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProductBatchUpdater
{
    /**
     * @var \Akeneo\Pim\ApiClient\AkeneoPimClientInterface
     */
    private $client;

    public function __construct(AkeneoPimClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws MissingParentException
     * @throws HttpException
     */
    public function update(array $resources, string $dataType)
    {
        $upsertedResources = $this->getApi($this->client, $dataType)->upsertList($resources);

        foreach ($upsertedResources as $response) {
            $this->checkUpsertedResourcesError($response['status_code'], $response['message'] ?? '');
        }
    }

    protected function getApi(AkeneoPimClientInterface $client, string $dataType): UpsertableResourceListInterface
    {
        if ('model' === $dataType) {
            return $client->getProductModelApi();
        }

        return $client->getProductApi();
    }

    /**
     * @throws MissingParentException
     * @throws HttpException
     */
    private function checkUpsertedResourcesError(int $statusCode, string $message)
    {
        if (Response::HTTP_UNPROCESSABLE_ENTITY === $statusCode &&

            false !== strpos($message, 'Property "parent" expects a valid parent code.') >= 0) {

            throw new MissingParentException($message, $statusCode);
        }

        if (400 <= $statusCode) {
            throw new HttpException($message, $statusCode);
        }
    }
}
