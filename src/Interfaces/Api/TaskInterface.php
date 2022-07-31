<?php

namespace Austomos\WriteForMePhp\Interfaces\Api;

use Austomos\WriteForMePhp\Interfaces\Api\Task\DetailedTaskSolutionsInterface;

interface TaskInterface
{
    public function detailedTaskSolutions(): DetailedTaskSolutionsInterface;
}
