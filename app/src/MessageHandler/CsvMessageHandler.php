<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ProductMessage;
use App\Queue\CsvWorker;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CsvMessageHandler
{
    public function __construct(
        private readonly CsvWorker $csvWorker,
        private readonly LoggerInterface $logger
    ) { }

    public function __invoke(ProductMessage $message): void
    {
        $product = $message->getProduct();

        try {
            // Process product to CSV.
            $this->csvWorker->process($product);
        } catch (\Exception $exception) {
            $this->logger->error("Failed to process product message: " . $exception->getMessage());
        }
    }
}
