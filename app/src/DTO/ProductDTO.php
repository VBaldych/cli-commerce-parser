<?php

declare(strict_types=1);

namespace App\DTO;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly string $imageUrl,
        public readonly string $productUrl
    ) {}
}