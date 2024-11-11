<?php

declare(strict_types=1);

namespace Unit\Service\Factory;

use App\Service\Factory\ParserFactory;
use App\Service\Parser\ParserInterface;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    public function testGetParser(): void
    {
        $parserMock = $this->createMock(ParserInterface::class);
        $factory = new ParserFactory(['Moyo' => $parserMock]);

        $parser = $factory->getParser('Moyo');
        $this->assertInstanceOf(ParserInterface::class, $parser);
    }

    public function testGetParserUnsupportedShop(): void
    {
        $this->expectException(\RuntimeException::class);

        $factory = new ParserFactory([]);
        $factory->getParser('UnsupportedShop');
    }
}
