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
    private Client $client;
    private array $login;
    private string $password;
    private string $username;

    public function __construct(string $username, string $password)
    {
        if (empty($this->username = $username)) {
            throw new InvalidArgumentException('Username cannot be empty', 400);
        }
        if (empty($this->password = $password)) {
            throw new InvalidArgumentException('Password cannot be empty', 400);
        }
        $this->client = new Client([
            'base_uri' => WriteForMe::BASE_URI,
        ]);
    }

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function login(): void
    {
        try {
            $response = $this->client->post('login', [
                'json' => [
                    'username' => $this->username,
                    'password' => $this->password,
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
        return isset($this->login['success']) && $this->login['success'] === true && !empty($this->login['token']);
    }

    public function getLogin(): array
    {
        return $this->login;
    }
}
