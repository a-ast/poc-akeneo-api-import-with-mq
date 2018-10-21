<?php

namespace App\Model;

class Product extends AbstractProduct
{
    public function __construct(string $family, string $identifier, string $modelCode, bool $enabled)
    {
        $currentDate = (new \DateTime())->format(DATE_W3C);

        $this->data = [
            'identifier' => $identifier,
            'family' => $family,
            'enabled' => $enabled,
            'parent' => $modelCode,
            'groups' => [],
            'categories' => [],
            'values' => [],
            'associations' => [],
            'created' => $currentDate,
            'updated' => $currentDate,
        ];
    }
}
