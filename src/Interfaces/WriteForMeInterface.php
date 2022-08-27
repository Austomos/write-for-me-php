<?php

namespace Austomos\WriteForMePhp\Interfaces;

use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;

interface WriteForMeInterface
{
    public static function login(): UserLoginInterface;
    public static function create(UserLoginInterface $userLogin): WriteForMeInterface;

    public function task(): TaskInterface;
}
