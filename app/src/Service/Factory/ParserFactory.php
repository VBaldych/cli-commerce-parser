<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\Service\Parser\ParserInterface;

/*
 * Actually, we don't need a factory for this task.
 * But, it's a good practise for future scalability.
 */
final readonly class ParserFactory
{
    public function __construct(
        private array $shops
    ) { }

    public function getParser(string $needle): ParserInterface
    {
        $shopNames = array_keys($this->shops);

        if (in_array($needle, $shopNames)) {
            return $this->shops[$needle];
        }

        throw new \RuntimeException(sprintf("Online shop isn't supported. The list of supported shops: %s", implode(', ', $shopNames)));
    }
}