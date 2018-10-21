<?php

namespace App\Model;

interface PimEntityInterface
{
    public function toStandardFormat(): array;
}
