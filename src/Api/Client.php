<?php

namespace Austomos\WriteForMePhp\Api;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\Interfaces\ClientInterface;
use Austomos\WriteForMePhp\Interfaces\ResponseInterface;
use Austomos\WriteForMePhp\WriteForMe;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use RuntimeException;

abstract class Client implements ClientInterface
{
    private GuzzleClient $guzzleClient;

    abstract public function getOptions(): array;
    abstract public function isValidOptions(): bool;
    abstract public function getMethod(): string;
    abstract public function getUri(): string|UriInterface;

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function __construct()
    {
        try {
            $this->guzzleClient = new GuzzleClient([
                'base_uri' => WriteForMe::BASE_URI,
                'headers' => [
                    'Authorization' => 'Bearer ' . WriteForMe::login()->getToken(),
                ]
            ]);
        } catch (RuntimeException $e) {
            throw new WriteForMeException(
                'You must login first, by calling WriteForMe::create() before calling WriteForMe::login()',
                400,
                $e
            );
        }
    }

    /**
     * @throws WriteForMeException
     */
    public function request(): ResponseInterface
    {
        if (!$this->isValidOptions()) {
            throw new InvalidArgumentException('Invalid arguments provided', 400);
        }
        try {
            return new Response($this->guzzleClient->request($this->getMethod(), $this->getUri(), $this->getOptions()));
        } catch (GuzzleException $e) {
            throw new WriteForMeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
