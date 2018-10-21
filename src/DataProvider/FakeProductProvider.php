<?php

namespace App\DataProvider;

use App\Model\DataProviderInterface;
use App\Model\Product;
use App\Model\ProductModel;
use Traversable;

class FakeProductProvider implements DataProviderInterface
{
    /**
     * @return \Traversable|Product[]|ProductModel[]
     */
    public function getData(): Traversable
    {
        for ($i = 0; $i < 1000; $i++) {

            $modelCode = sprintf('product-model-%s', $this->getRandomCode());

            $model = new ProductModel('clothing_size', $modelCode, null);
            $model->addValue('name', sprintf('Product model %d', $i));

            yield $model;

            $axes = ['s', 'm', 'l', 'xl', 'xxl'];

            foreach ($axes as $axis) {

                $identifier = sprintf('product-%s-%s', $modelCode, $axis);

                $product = new Product('clothing', $identifier, $modelCode, false);
                $product->addValue('size', $axis);

                yield $product;
            }
        }
    }

    private function getRandomCode(): string
    {
        return bin2hex(random_bytes(10));
    }

}
