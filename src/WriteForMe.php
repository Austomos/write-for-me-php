<?php

namespace Austomos\WriteForMePhp;

use Austomos\WriteForMePhp\Interfaces\UserLoginInterface;
use Austomos\WriteForMePhp\Interfaces\WriteForMeInterface;
use GuzzleHttp\Client;

class WriteForMe implements WriteForMeInterface
{
    public const BASE_URI = 'https://api.writeforme.org/api/v1';
    protected static UserLoginInterface $login;
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Authorization' => 'Bearer ' . self::$login->getToken(),
            ]
        ]);
    }

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public static function create(string $username, string $password): WriteForMeInterface
    {
        self::$login = new UserLogin($username, $password);
        return new static();
    }


}