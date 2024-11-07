<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Message\ProductMessage;
use App\MessageHandler\CsvMessageHandler;
use App\Queue\CsvWorker;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use App\DTO\ProductDTO;

class CsvMessageHandlerTest extends TestCase
{
    public function testInvoke()
    {
        $csvWorkerMock = $this->createMock(CsvWorker::class);
        $loggerMock = $this->createMock(LoggerInterface::class);

        $csvWorkerMock->expects($this->once())->method('process');

        $handler = new CsvMessageHandler($csvWorkerMock, $loggerMock);
        $product = new ProductDTO('Product1', 100.0, 'url1', 'url2');
        $message = new ProductMessage($product);

        $handler($message);
    }
}
