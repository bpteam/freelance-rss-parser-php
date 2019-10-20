<?php

namespace Freelance\Loader;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class GuzzleLoader implements LoaderInterface
{
    /**
     * @var ClientInterface
     */
    private $guzzle;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ClientInterface $guzzle,
        LoggerInterface $logger
    ) {
        $this->guzzle = $guzzle;
        $this->logger = $logger;
    }

    /**
     * @param string $feedUrl
     * @return string
     */
    public function load(string $feedUrl): string
    {
        try {
            $response = $this->guzzle->request('GET', $feedUrl, [
                'headers' => [
                    'Accept-language' => 'en',
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) snap Chromium/77.0.3865.90 Chrome/77.0.3865.90 Safari/537.36',
                ]
            ])->getBody();
        } catch (GuzzleException $e) {
            $this->logger->error(
                'Loader guzzle error: ' . $e->getCode(),
                [
                    'message' => $e->getMessage(),
                ]
            );
        }

        return $response ?? '';
    }
}