<?php

namespace Austomos\WriteForMePhp\Api\Task;

use Austomos\WriteForMePhp\Api\Client;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Http\Message\UriInterface;

class MyTasks extends Client
{
    private int $limit = 10;
    private string $query = '';
    private int $skip = 0;
    private bool $sortAsc = false;
    private string $sortBy = 'created';

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function setSkip(int $skip): self
    {
        $this->skip = $skip;
        return $this;
    }

    public function setSortAsc(bool $sortAsc): self
    {
        $this->sortAsc = $sortAsc;
        return $this;
    }

    public function setSortBy(string $sortBy): self
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    #[ArrayShape(['json' => 'array'])] public function getOptions(): array
    {
        return [
            'json' => [
                'limit' => $this->limit,
                'listType' => 'myTasks',
                'query' => $this->query,
                'skip' => $this->skip,
                'sortAsc' => $this->sortAsc,
                'sortBy' => $this->sortBy,
            ],
        ];
    }

    public function isValidOptions(): bool
    {
        return true;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getUri(): string|UriInterface
    {
        return 'tasks';
    }
}
