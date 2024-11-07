<?php

declare(strict_types=1);

namespace App\Queue;

use App\DTO\ProductDTO;
use App\Service\Csv\CsvFileService;
use Psr\Log\LoggerInterface;

class CsvWorker
{
    public function __construct(
        private readonly CsvFileService $csvStorage,
        private readonly LoggerInterface $logger
    ) { }

    public function process(ProductDTO $product): void
    {
        try {
            $this->csvStorage->saveProduct($product);
        } catch (\Exception $exception) {
            $this->logger->error("Failed to save product data to CSV: " . $exception->getMessage());
        }
    }
}
