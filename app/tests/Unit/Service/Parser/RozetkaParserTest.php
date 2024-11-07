<?php

declare(strict_types=1);

namespace App\Tests\Service\Parser;

use App\DTO\ProductDTO;
use App\Service\Parser\RozetkaParser;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RozetkaParserTest extends TestCase
{
    private RozetkaParser $parser;

    protected function setUp(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $this->parser = new RozetkaParser($logger);
    }

    public function testParse(): void
    {
        $htmlContent = <<<HTML
        <ul class="catalog-grid">
            <li class="catalog-grid__cell">
                <span class="goods-tile__title">Product 1</span>
                <span class="goods-tile__price-value">123.45</span>
                <img class="ng-lazyloaded" src="https://rozetka.com.ua/mobile-phones/c80003/image1.jpg" />
                <a class="goods-tile__picture" href="https://rozetka.com.ua/mobile-phones/c80003/product1"></a>
            </li>
            <li class="catalog-grid__cell">
                <span class="goods-tile__title">Product 2</span>
                <span class="goods-tile__price-value">67.89</span>
                <img class="ng-lazyloaded" src="https://rozetka.com.ua/mobile-phones/c80003/image2.jpg" />
                <a class="goods-tile__picture" href="https://rozetka.com.ua/mobile-phones/c80003/product2"></a>
            </li>
        </ul>
        HTML;

        $parserMock = $this->getMockBuilder(RozetkaParser::class)
            ->setConstructorArgs([$this->createMock(LoggerInterface::class)])
            ->onlyMethods(['getHtml'])
            ->getMock();

        $parserMock->expects($this->once())
            ->method('getHtml')
            ->willReturn($htmlContent);

        $products = $parserMock->parse('https://rozetka.com.ua/mobile-phones/c80003');

        $this->assertCount(2, $products);

        $this->assertInstanceOf(ProductDTO::class, $products[0]);
        $this->assertEquals('Product 1', $products[0]->name);
        $this->assertEquals(123.45, $products[0]->price);
        $this->assertEquals('https://rozetka.com.ua/mobile-phones/c80003/image1.jpg', $products[0]->imageUrl);
        $this->assertEquals('https://rozetka.com.ua/mobile-phones/c80003/product1', $products[0]->productUrl);

        $this->assertInstanceOf(ProductDTO::class, $products[1]);
        $this->assertEquals('Product 2', $products[1]->name);
        $this->assertEquals(67.89, $products[1]->price);
        $this->assertEquals('https://rozetka.com.ua/mobile-phones/c80003/image2.jpg', $products[1]->imageUrl);
        $this->assertEquals('https://rozetka.com.ua/mobile-phones/c80003/product2', $products[1]->productUrl);
    }
}
