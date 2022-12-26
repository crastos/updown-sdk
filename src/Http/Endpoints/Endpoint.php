<?php

namespace Crastos\Updown\Http\Endpoints;

use Crastos\Updown\Updown;

abstract class Endpoint
{
    /** @var class-string<\Crastos\Updown\Http\Resources\Resource> */
    protected string $resource_class;

    public function __construct(protected Updown $updown)
    {
    }

    public function __get(string $name)
    {
        return $this->updown->{$name};
    }

    public function __call(string $name, array $args)
    {
        return $this->updown->{$name}(...$args);
    }
}
