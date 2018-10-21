<?php

namespace App\Model;

use App\Model\PimEntityInterface;
use Traversable;

interface DataProviderInterface
{
    /**
     * @return Traversable|PimEntityInterface[]
     */
    public function getData(): Traversable;
}
