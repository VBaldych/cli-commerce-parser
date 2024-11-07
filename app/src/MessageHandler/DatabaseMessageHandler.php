<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ProductMessage;
use App\Queue\DatabaseWorker;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DatabaseMessageHandler
{
    public function __construct(
        private readonly DatabaseWorker $databaseWorker,
        private readonly LoggerInterface $logger
    ) { }

    public function __invoke(ProductMessage $message): void
    {
        $product = $message->getProduct();

        try {
            $this->databaseWorker->process($product);
        } catch (\Exception $exception) {
            $this->logger->error("Failed to process product message: " . $exception->getMessage());
        }
    }
}
