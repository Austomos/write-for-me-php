<?php

namespace Austomos\WriteForMePhp\Api;

use Austomos\WriteForMePhp\Api\Task\DetailedTaskSolutions;
use Austomos\WriteForMePhp\Interfaces\Api\Task\DetailedTaskSolutionsInterface;
use Austomos\WriteForMePhp\Interfaces\Api\TaskInterface;

class Task implements TaskInterface
{


    public function detailedTaskSolutions(): DetailedTaskSolutionsInterface
    {
        return new DetailedTaskSolutions();
    }

}
