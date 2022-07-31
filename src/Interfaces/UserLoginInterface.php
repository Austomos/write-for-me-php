<?php

namespace Austomos\WriteForMePhp\Interfaces;

use GuzzleHttp\Client;

interface UserLoginInterface
{
    public function __construct(string $username, string $password);
    public function login(Client $client, string $username, string $password): void;

    public function getToken(): string;
    public function getUsername(): string;
    public function getUserId(): string;
    public function isConnected(): bool;
    public function getLogin(): array;

}
