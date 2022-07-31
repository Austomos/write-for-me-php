<?php

namespace Austomos\WriteForMePhp\Interfaces\Api\Task;

interface DetailedTaskSolutionsInterface
{
    public function setLimit(int $limit): self;

    public function setQuery(string $query): self;

    public function setSkip(int $skip): self;

    public function setSortAsc(bool $sortAsc): self;

    public function setSortBy(string $sortBy): self;

    public function setTask(string $task): self;
}
