<?php

namespace App\Model;

abstract class AbstractProduct implements PimEntityInterface
{
    protected $data = [];

    public function toStandardFormat(): array
    {
        return $this->data;
    }

    public function addValue(string $attributeCode, $data, ?string $locale = null, ?string $scope = null): self
    {
        $this->data['values'][$attributeCode] = [
             [
                 'locale' => $locale,
                 'scope' => $scope,
                 'data' => $data,
             ]
        ];

        return $this;
    }
}
