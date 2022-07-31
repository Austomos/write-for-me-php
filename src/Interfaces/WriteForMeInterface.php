<?php

namespace Austomos\WriteForMePhp\Interfaces;

use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;

interface WriteForMeInterface
{
    public static function login(): UserLoginInterface;
    public static function create(string $username, string $password): WriteForMeInterface;

    public function task(): TaskInterface;

}
