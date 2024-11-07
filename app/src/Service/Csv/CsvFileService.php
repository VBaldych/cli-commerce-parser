<?php

declare(strict_types=1);

namespace App\Service\Csv;

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
        if ($this->isProductInCsv($product->productUrl)) {
            $this->logger->info("Product with URL {$product->productUrl} already exists in CSV.");
            return;
        }

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

    private function isProductInCsv(string $productUrl): bool
    {
        try {
            $file = fopen($this->filePath, 'r');

            if ($file === false) {
                throw new \RuntimeException("Unable to open CSV file for reading.");
            }

            fgetcsv($file);

            while ($row = fgetcsv($file)) {
                // Check if product already exists in CSV.
                if (isset($row[3]) && $row[3] === $productUrl) {
                    fclose($file);

                    return true;
                }
            }

            fclose($file);

            return false;
        } catch (\Exception $exception) {
            $this->logger->error("Failed to read CSV file: " . $exception->getMessage());

            return false;
        }
    }
}
