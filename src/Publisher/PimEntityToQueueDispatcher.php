<?php

namespace App\Publisher;

use App\Model\PimEntityDispatcherInterface;
use App\Model\PimEntityInterface;
use App\Model\ProductModel;

class PimEntityToQueueDispatcher implements PimEntityDispatcherInterface
{

    public function getDestination(PimEntityInterface $pimEntity): string
    {
        if ($pimEntity instanceof ProductModel) {
            return 'create_product_model_queue';
        }

        return 'create_product_queue';
    }
}
