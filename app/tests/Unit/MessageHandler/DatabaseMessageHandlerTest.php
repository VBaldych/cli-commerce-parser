<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Message\ProductMessage;
use App\MessageHandler\DatabaseMessageHandler;
use App\Queue\DatabaseWorker;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use App\DTO\ProductDTO;

class DatabaseMessageHandlerTest extends TestCase
{
    public function testInvoke()
    {
        $databaseWorkerMock = $this->createMock(DatabaseWorker::class);
        $loggerMock = $this->createMock(LoggerInterface::class);

        $databaseWorkerMock->expects($this->once())->method('process');

        $handler = new DatabaseMessageHandler($databaseWorkerMock, $loggerMock);
        $product = new ProductDTO('Product1', 100.0, 'url1', 'url2');
        $message = new ProductMessage($product);

        $handler($message);
    }
}
