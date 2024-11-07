<?php

declare(strict_types=1);

namespace App\Message;

use App\DTO\ProductDTO;

class ProductMessage
{
    public function __construct(
        private readonly ProductDTO $product
    ) { }

    public function getProduct(): ProductDTO
    {
        return $this->product;
    }
}
