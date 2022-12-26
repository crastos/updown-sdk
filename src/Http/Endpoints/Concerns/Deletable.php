<?php

namespace Crastos\Updown\Http\Endpoints\Concerns;

use Crastos\Updown\Http\Resources\Resource;
use Crastos\Updown\Updown;

/**
 * @property Updown $updown
 */
trait Deletable
{
    public function delete(string|int|Resource $resource)
    {
        if ($resource instanceof Resource) {
            $resource = $resource->{$resource->foreignKey()};
        }

        return $this->updown->client->delete("{$this->updown->path}/{$resource}");
    }
}
