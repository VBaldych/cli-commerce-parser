<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProductRepositoryTest extends TestCase
{
    public function testSaveOrUpdateProduct()
    {
        $productDTO = new ProductDTO('Product1', 100.0, 'url1', 'url2');

        $product = new Product();
        $product->setName($productDTO->name)
            ->setPrice($productDTO->price)
            ->setImageUrl($productDTO->imageUrl)
            ->setProductUrl($productDTO->productUrl);

        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $loggerMock = $this->createMock(LoggerInterface::class);

        $repository = new ProductRepository($managerRegistryMock, $loggerMock);

        $this->assertInstanceOf(ProductRepository::class, $repository);
    }
}
