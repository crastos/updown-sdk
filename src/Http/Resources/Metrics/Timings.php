<?php

namespace Crastos\Updown\Http\Resources\Metrics;

use Crastos\Updown\Http\Resources\Resource;

class Timings extends Resource
{
    public readonly int $redirect;

    public readonly int $namelookup;

    public readonly int $connection;

    public readonly int $handshake;

    public readonly int $response;

    public readonly int $total;

    public function __construct(iterable $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
