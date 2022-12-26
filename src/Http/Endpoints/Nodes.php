<?php

namespace Crastos\Updown\Http\Endpoints;

class Nodes extends Endpoint
{
    use Concerns\Browseable;

    protected string $resource_class = \Crastos\Updown\Http\Resources\Check::class;

    public function ipv4()
    {
        return $this->updown->client->get("{$this->updown->path}/ipv4");
    }

    public function ipv6()
    {
        return $this->updown->client->get("{$this->updown->path}/ipv6");
    }
}
