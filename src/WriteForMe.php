<?php

namespace Austomos\WriteForMePhp;

use Austomos\WriteForMePhp\Api\Task;
use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;
use Austomos\WriteForMePhp\Interfaces\UserLoginInterface;
use Austomos\WriteForMePhp\Interfaces\WriteForMeInterface;
use RuntimeException;

class WriteForMe implements WriteForMeInterface
{
    public const BASE_URI = 'https://api.writeforme.org/api/v1/';
    protected static UserLoginInterface $login;
    private static WriteForMe $factory;

    public static function create(UserLoginInterface $userLogin): WriteForMeInterface
    {
        self::$login = $userLogin;
        self::$factory = new static();
        return self::$factory;
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

    public static function factory(): WriteForMeInterface
    {
        if (!isset(self::$factory)) {
            throw new RuntimeException(
                'You must create factory first, by calling WriteForMe::create()',
                400
            );
        }
        return self::$factory;
    }

    public function task(): TaskInterface
    {
        return new Task();
    }
}
