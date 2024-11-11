<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\DTO\ProductDTO;
use Symfony\Component\DomCrawler\Crawler;

final class MoyoParser extends ParserBase
{
    public const BASE_URL = 'https://www.moyo.ua';

    public function parse(string $url): array
    {
        $html = $this->getHtml($url);
        $crawler = new Crawler($html);
        $products = [];

        try {
            $crawler->filterXPath('//div[contains(@class, "goods-item")]')->each(function (Crawler $node) use (&$products): void {
                try {
                    $name = $node->attr('data-name');
                    $price = (float) $node->attr('data-price');
                    $imageUrl = $node->attr('data-img');
                    $productUrl = self::BASE_URL . $node->filterXPath('//a[contains(@class, "gtm-link-product")]')->attr('href');

                    $products[] = new ProductDTO($name, $price, $imageUrl, $productUrl);
                } catch (\Exception $exception) {
                    $this->logger->warning("Failed to parse product data: " . $exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            $this->logger->critical("Failed to parse the HTML content: " . $exception->getMessage());
            throw new \RuntimeException("Parsing failed due to critical error. ", $exception->getCode(), $exception);
        }

        return $products;
    }

}
