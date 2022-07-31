<?php

namespace Austomos\WriteForMePhp\Interfaces;

use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;

interface ResponseInterface
{
    public function __construct(GuzzleResponseInterface $response);
    public function getResponseBody(): array;
}
