<?php

namespace App\Tests\Service\Factory;

use App\Service\Factory\ParserFactory;
use App\Service\Parser\ParserInterface;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    public function testGetParser()
    {
        $parserMock = $this->createMock(ParserInterface::class);
        $factory = new ParserFactory(['Rozetka' => $parserMock]);

        $parser = $factory->getParser('Rozetka');
        $this->assertInstanceOf(ParserInterface::class, $parser);
    }

    public function testGetParserUnsupportedShop()
    {
        $this->expectException(\RuntimeException::class);

        $factory = new ParserFactory([]);
        $factory->getParser('UnsupportedShop');
    }
}
