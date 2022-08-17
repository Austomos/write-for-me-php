<?php

namespace Austomos\WriteForMePhp\Interfaces;

use Psr\Http\Message\UriInterface;

interface ClientInterface
{
    public function request(): ResponseInterface;
    public function getOptions(): array;
    public function isValidOptions(): bool;
    public function getMethod(): string;
    public function getUri(): string|UriInterface;
}
