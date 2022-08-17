<?php

namespace Austomos\WriteForMePhp\Interfaces;

use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;

interface ResponseInterface
{
    public function __construct(GuzzleResponseInterface $response);
    public function response(): GuzzleResponseInterface;
    public function getResponseBody(): array|object;
    public function getResponseBodyObject(): object;
    public function getResponseBodyArray(): array;
}
