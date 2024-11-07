<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\DTO\ProductDTO;
use Symfony\Component\DomCrawler\Crawler;

final class RozetkaParser extends ParserBase
{
    public function parse(string $url): array
    {
        $html = $this->getHtml($url);
        $crawler = new Crawler($html);
        $products = [];

        try {
            $crawler->filterXPath('//li[contains(@class, "catalog-grid__cell")]')->each(function (Crawler $node) use (&$products): void {
                try {
                    $name = $node->filterXPath('.//span[contains(@class, "goods-tile__title")]')->text();
                    $price = (float) $node->filterXPath('.//span[contains(@class, "goods-tile__price-value")]')->text();
                    $imageUrl = $node->filterXPath('.//img[contains(@class, "ng-lazyloaded")]')->attr('src');
                    $productUrl = $node->filterXPath('.//a[contains(@class, "goods-tile__picture")]')->attr('href');

                    $products[] = new ProductDTO($name, $price, $imageUrl, $productUrl);
                } catch (\Exception $e) {
                    $this->logger->warning("Failed to parse product data: " . $e->getMessage());
                }
            });
        } catch (\Exception $exception) {
            $this->logger->critical("Failed to parse the HTML content: " . $exception->getMessage());
            throw new \RuntimeException("Parsing failed due to critical error.", $exception->getCode(), $exception);
        }

        return $products;
    }
}
