<?php

namespace Crastos\Updown;

use Crastos\Updown\Concerns\GeneratesClassNames;
use Crastos\Updown\Http\Client;
use Crastos\Updown\Http\Endpoints\Endpoint;

class Updown
{
    use GeneratesClassNames;

    public function __construct(public readonly Client $client, public readonly string $path = '')
    {
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    public function __call(string $name, array $payload)
    {
        return ($this->{$name})(...$payload);
    }

    public function __toString()
    {
        return $this->path;
    }

    protected function get($path = ''): Endpoint|static
    {
        $api = new static($this->client, "{$this->path}/{$path}");
        $endpointClassName = "\\Crastos\\Updown\\Http\\Endpoints\\{$this->generateClassName($api->path)}";

        return class_exists($endpointClassName) ? new $endpointClassName($api) : $api;
    }
}
