<?php

namespace Crastos\Updown\Http\Endpoints;

class Checks extends Endpoint
{
    use Concerns\Browseable;
    use Concerns\Createable;
    use Concerns\Readable;
    use Concerns\Editable;
    use Concerns\Deletable;

    protected string $resource_class = \Crastos\Updown\Http\Resources\Check::class;

    public function downtimes(string|int|self $resource, iterable $payload = [])
    {
        if ($resource instanceof self) {
            $resource = $resource->{$resource->foreignKey()};
        }

        return $this->updown->client->get("{$this->updown->path}/{$resource}/downtimes", (array) $payload);
    }

    public function metrics(string|int|self $resource, iterable $payload = [])
    {
        if ($resource instanceof self) {
            $resource = $resource->{$resource->foreignKey()};
        }

        return $this->updown->client->get("{$this->updown->path}/{$resource}/metrics", (array) $payload);
    }
}
