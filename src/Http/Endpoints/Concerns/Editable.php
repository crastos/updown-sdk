<?php

namespace Crastos\Updown\Http\Endpoints\Concerns;

use Crastos\Updown\Http\Resources\Resource;
use Crastos\Updown\Updown;

/**
 * @property Updown $updown
 */
trait Editable
{
    public function update(string|int|Resource $resource, iterable $payload = [])
    {
        if ($resource instanceof Resource) {
            $resource = $resource->{$resource->foreignKey()};
        }

        return $this->updown->client->put("{$this->updown->path}/{$resource}", (array) $payload);
    }

    public function edit(string|int|Resource $resource, iterable $payload = [])
    {
        if ($resource instanceof Resource) {
            $payload = ((array) $resource) + ((array) $payload);
            $resource = $resource->{$resource->foreignKey()};
        }

        return $this->updown->client->patch("{$this->updown->path}/{$resource}", (array) $payload);
    }
}
