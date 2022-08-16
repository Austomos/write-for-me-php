<?php

namespace Austomos\WriteForMePhp;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;
use Austomos\WriteForMePhp\Interfaces\UserLoginInterface;
use Austomos\WriteForMePhp\Interfaces\WriteForMeInterface;
use GuzzleHttp\Client;
use RuntimeException;

class WriteForMe implements WriteForMeInterface
{
    public const BASE_URI = 'https://api.writeforme.org/api/v1/';
    protected static UserLoginInterface $login;

    public static function create(UserLoginInterface $userLogin): void
    {
        self::$login = $userLogin;
    }

    public static function login(): UserLoginInterface
    {
        if (!isset(self::$login) || !self::$login->isConnected()) {
            throw new RuntimeException(
                'You must login first, by calling WriteForMe::create() before calling WriteForMe::login()',
                400
            );
        }
        return self::$login;
    }

    public function task(): TaskInterface
    {
        return new Task();
    }
}
