<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\ProductDTO;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($registry, Product::class);
    }

    public function saveOrUpdateProduct(ProductDTO $productDTO): void
    {
        try {
            $product = $this->findOneBy(['product_url' => $productDTO->productUrl]) ?? new Product();

            $product
                ->setName($productDTO->name)
                ->setPrice($productDTO->price)
                ->setImageUrl($productDTO->imageUrl)
                ->setProductUrl($productDTO->productUrl);

            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();
        } catch (\Exception $exception) {
            $this->logger->error("Failed to save or update product: " . $exception->getMessage());
        }
    }
}
