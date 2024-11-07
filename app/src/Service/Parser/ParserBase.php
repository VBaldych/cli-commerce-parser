<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\DTO\ProductDTO;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;

abstract class ParserBase implements ParserInterface
{
    public function __construct(
        protected LoggerInterface $logger,
    ) { }

    public function getHtml(string $url): ?string
    {
        try {
            $client = HttpClient::create();
            $response = $client->request('GET', $url);

            return $response->getContent();
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('Error loading page content from %s: ', $url) . $exception->getMessage());
            return null;
        }
    }

    /**
     * Parse HTML content.
     *
     * @param string $htmlContent
     * @return ProductDTO[]
     */
    abstract public function parse(string $url): array;
}