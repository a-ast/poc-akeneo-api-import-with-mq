<?php

namespace App\Model;

interface PimEntityDispatcherInterface
{
    public function getDestination(PimEntityInterface $pimEntity): string;
}
