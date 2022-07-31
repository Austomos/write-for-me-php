<?php

namespace Austomos\WriteForMePhp\Api;

use Austomos\WriteForMePhp\Api\Task\DetailedTaskSolutions;
use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;

class Task implements TaskInterface
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Austomos\WriteForMePhp\Exceptions\WriteForMeException
     */
    public function detailedTaskSolutions(): DetailedTaskSolutions
    {
        return new DetailedTaskSolutions();
    }
}