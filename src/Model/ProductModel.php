<?php

namespace App\Model;

class ProductModel extends AbstractProduct
{
    public function __construct(string $familyVariant, string $code, ?string $parentModelCode)
    {
        $currentDate = (new \DateTime())->format(DATE_W3C);

        $this->data = [
            'code' => $code,
            'family_variant' => $familyVariant,
            'parent' => $parentModelCode,
            'categories' => [],
            'values' => [],
            'associations' => [],
            'created' => $currentDate,
            'updated' => $currentDate,
        ];
    }
}
