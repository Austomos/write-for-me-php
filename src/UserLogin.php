<?php

namespace Austomos\WriteForMePhp;

use Austomos\WriteForMePhp\Exceptions\WriteForMeException;
use Austomos\WriteForMePhp\Interfaces\UserLoginInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use JsonException;
use Psr\Http\Message\ResponseInterface;

class UserLogin implements UserLoginInterface
{
    private array $login;

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function login(Client $client, string $username, string $password): void
    {
        if (empty($username) || empty($password)) {
            throw new InvalidArgumentException('Username and password are required');
        }
        try {
            $response = $client->post('/login', [
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);
        } catch (GuzzleException $e) {
            throw new WriteForMeException($e->getMessage(), $e->getCode(), $e);
        }
        $this->response($response);
    }

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    private function response(ResponseInterface $response): void
    {
        try {
            $this->login = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY
            );
        } catch (JsonException $e) {
            throw new WriteForMeException($e->getMessage(), $e->getCode(), $e);
        }
        if ($this->login['success'] !== true) {
            throw new WriteForMeException('Login failed: ' . $this->login['message'], 400);
        }
    }

    public function getToken(): string
    {
        return $this->login['token'];
    }

    public function getUsername(): string
    {
        return $this->login['username'];
    }

    public function getUserId(): string
    {
        return $this->login['id'];
    }

    public function isConnected(): bool
    {
        return $this->login['success'] && !empty($this->login['token']);
    }

    public function getLogin(): array
    {
        return $this->login;
    }
}
