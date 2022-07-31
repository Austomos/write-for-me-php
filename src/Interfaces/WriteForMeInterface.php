<?php

namespace Austomos\WriteForMePhp\Interfaces;

interface WriteForMeInterface
{
    public function __construct();
    public static function create(string $username, string $password): WriteForMeInterface;

}
