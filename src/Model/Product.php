<?php

namespace App\Model;

class Product
{
    private $data = [];

    public function __construct(string $identifier, string $family, bool $enabled)
    {
        $currentDate = (new \DateTime())->format(DATE_W3C);
        $this->data = [
            'identifier' => $identifier,
            'family' => $family,
            'enabled' => $enabled,
            'parent' => null,
            'groups' => [],
            'categories' => [],
            'values' => [],
            'associations' => [],
            'created' => $currentDate,
            'updated' => $currentDate,
        ];
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
