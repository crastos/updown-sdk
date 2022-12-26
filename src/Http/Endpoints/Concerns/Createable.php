<?php

namespace Crastos\Updown\Http\Endpoints\Concerns;

use Crastos\Updown\Http\Resources\Resource;
use Crastos\Updown\Updown;

/**
 * @property Updown $updown
 * @property class-string<\Crastos\Updown\Http\Resources\Resource> $resource_class
 */
trait Createable
{
    public function create(iterable|Resource $resource)
    {
        if (! $resource instanceof Resource) {
            $resource = new $this->resource_class($resource);
        }

        return $this->updown->client->post($this->updown->path, (array) $resource);
    }
}
