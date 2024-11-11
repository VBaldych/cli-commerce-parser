<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\DTO\ProductDTO;
use Psr\Log\LoggerInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\HttpClient\HttpClient;

abstract class ParserBase implements ParserInterface
{
    public const BASE_URL = '';

    public function __construct(
        protected LoggerInterface $logger,
        protected HtmlSanitizerInterface $htmlSanitizer
    ) { }

    public function getHtml(string $url): ?string
    {
        // Check is category URL related to base URL.
        if (!str_contains($url, (string) static::BASE_URL)) {
            $this->logger->error(sprintf("URL %s isn't related to base URL %s", $url, static::BASE_URL));

            return null;
        }

        try {
            $client = HttpClient::create();
            $request = $client->request('GET', $url);

            return $request->getContent();
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
