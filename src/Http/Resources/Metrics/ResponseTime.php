<?php

namespace Crastos\Updown\Http\Resources\Metrics;

use Crastos\Updown\Http\Resources\Resource;

class ResponseTime extends Resource
{
    public readonly int $under125;

    public readonly int $under250;

    public readonly int $under500;

    public readonly int $under1000;

    public readonly int $under2000;

    public readonly int $under4000;

    public function __construct(iterable $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
