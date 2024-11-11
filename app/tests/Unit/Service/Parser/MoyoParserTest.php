<?php

declare(strict_types=1);

namespace App\Tests\Service\Parser;

use App\DTO\ProductDTO;
use App\Service\Parser\MoyoParser;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class MoyoParserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->createMock(LoggerInterface::class);
        $this->createMock(HtmlSanitizerInterface::class);
    }

    public function testParse(): void
    {
        $htmlContent = <<<HTML
        <div class="goods-item" data-name="Product 1" data-price="123.45" data-img="https://moyo.ua/images/product1.jpg">
            <a class="gtm-link-product" href="/mobile-phones/product1"></a>
        </div>
        <div class="goods-item" data-name="Product 2" data-price="67.89" data-img="https://moyo.ua/images/product2.jpg">
            <a class="gtm-link-product" href="/mobile-phones/product2"></a>
        </div>
        HTML;

        $parserMock = $this->getMockBuilder(MoyoParser::class)
            ->setConstructorArgs([$this->createMock(LoggerInterface::class), $this->createMock(HtmlSanitizerInterface::class)])
            ->onlyMethods(['getHtml'])
            ->getMock();

        $parserMock->expects($this->once())
            ->method('getHtml')
            ->willReturn($htmlContent);

        $products = $parserMock->parse('https://www.moyo.ua/mobile-phones');

        $this->assertCount(2, $products);

        $this->assertInstanceOf(ProductDTO::class, $products[0]);
        $this->assertSame('Product 1', $products[0]->name);
        $this->assertEqualsWithDelta(123.45, $products[0]->price, PHP_FLOAT_EPSILON);
        $this->assertSame('https://moyo.ua/images/product1.jpg', $products[0]->imageUrl);
        $this->assertSame('https://www.moyo.ua/mobile-phones/product1', $products[0]->productUrl);

        $this->assertInstanceOf(ProductDTO::class, $products[1]);
        $this->assertSame('Product 2', $products[1]->name);
        $this->assertEqualsWithDelta(67.89, $products[1]->price, PHP_FLOAT_EPSILON);
        $this->assertSame('https://moyo.ua/images/product2.jpg', $products[1]->imageUrl);
        $this->assertSame('https://www.moyo.ua/mobile-phones/product2', $products[1]->productUrl);
    }
}
