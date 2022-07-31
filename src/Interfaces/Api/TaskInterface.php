<?php

namespace Austomos\WriteForMePhp\Interfaces\Api;

use Austomos\WriteForMePhp\Api\Task\DetailedTaskSolutions;

interface TaskInterface
{
    public function detailedTaskSolutions(): DetailedTaskSolutions;
}
