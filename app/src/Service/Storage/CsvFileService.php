<?php

declare(strict_types=1);

namespace App\Service\Storage;

use App\DTO\ProductDTO;
use Psr\Log\LoggerInterface;

class CsvFileService
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $filePath
    ) {
        if (!file_exists($this->filePath)) {
            $this->initializeCsvFile();
        }
    }

    private function initializeCsvFile(): void
    {
        $header = ['Name', 'Price', 'Image URL', 'Product URL'];
        $this->saveRow($header);
    }

    public function saveProduct(ProductDTO $product): void
    {
        $row = [
            $product->name,
            $product->price,
            $product->imageUrl,
            $product->productUrl
        ];

        $this->saveRow($row);
    }

    private function saveRow(array $data): void
    {
        try {
            $file = fopen($this->filePath, 'a');

            if ($file === false) {
                throw new \RuntimeException("Unable to open CSV file for writing.");
            }

            fputcsv($file, $data);
            fclose($file);
        } catch (\Exception $exception) {
            $this->logger->error("Failed to save data to CSV: " . $exception->getMessage());
        }
    }
}
