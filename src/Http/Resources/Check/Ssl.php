<?php

namespace Crastos\Updown\Http\Resources\Check;

use Crastos\Updown\Http\Resources\Resource;
use DateTimeInterface;

class Ssl extends Resource
{
    public readonly DateTimeInterface $tested_at;

    public readonly DateTimeInterface $expires_at;

    public readonly bool $valid;

    public readonly string $error;

    public function __construct(iterable $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
