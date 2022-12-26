<?php

namespace Crastos\Updown\Http\Endpoints\Concerns;

use Crastos\Updown\Http\Resources\Resource;
use Crastos\Updown\Http\Resources\ResourceRepository;
use Crastos\Updown\Updown;

/**
 * @property Updown $updown
 */
trait Browseable
{
    /** @var class-string<\Crastos\Updown\Http\Resources\ResourceRepository> */
    protected string $repository_class = \Crastos\Updown\Http\Resources\ResourceRepository::class;

    protected ResourceRepository $repository;

    public function all(): ResourceRepository
    {
        return $this->repository ??= new $this->repository_class(
            $this->updown->client->get($this->updown->path),
            $this->resource_class
        );
    }

    public function first(): ?Resource
    {
        return $this->all()->first();
    }

    public function last(): ?Resource
    {
        return $this->all()->last();
    }

    public function filter(?callable $callback, $mode = 0): ResourceRepository
    {
        return $this->all()->filter($callback, $mode);
    }

    public function find(string $id): ?Resource
    {
        return $this->all()->find($id);
    }
}
