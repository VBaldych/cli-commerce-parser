<?php

declare(strict_types=1);

namespace App\Queue;

use App\DTO\ProductDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DatabaseWorker
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {}

    public function process(ProductDTO $product): void
    {
        try {
            $this->entityManager->getRepository(Product::class)->saveOrUpdateProduct($product);
        } catch (\Exception $exception) {
            $this->logger->error("Failed to save product data to DB: " . $exception->getMessage());
        }
    }
}
