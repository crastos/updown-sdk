<?php

namespace Crastos\Updown\Http\Resources;

use DateTimeInterface;

class Downtime extends Resource
{
    public readonly string $id;

    public readonly string $error;

    public readonly DateTimeInterface $started_at;

    public readonly DateTimeInterface $ended_at;

    public readonly int $duration;

    public readonly bool $partial;

    public function __construct(iterable|string|int $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
