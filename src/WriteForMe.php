<?php

namespace Austomos\WriteForMePhp;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;
use Austomos\WriteForMePhp\Interfaces\UserLoginInterface;
use Austomos\WriteForMePhp\Interfaces\WriteForMeInterface;
use GuzzleHttp\Client;

class WriteForMe implements WriteForMeInterface
{
    public const BASE_URI = 'https://api.writeforme.org/api/v1';
    protected static UserLoginInterface $login;

    /**
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public static function create(string $username, string $password): WriteForMeInterface
    {
        self::$login = new UserLogin();
        $client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
        self::$login->login($client, $username, $password);
        return new static();
    }

    public static function login(): UserLoginInterface
    {
        return self::$login;
    }

    public function task(): TaskInterface
    {
        return new Task();
    }
}
