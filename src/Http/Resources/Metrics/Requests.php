<?php

namespace Crastos\Updown\Http\Resources\Metrics;

use Crastos\Updown\Http\Resources\Resource;

class Requests extends Resource
{
    public readonly int $samples;

    public readonly int $failures;

    public readonly int $satisfied;

    public readonly int $tolerated;

    public readonly ResponseTime $by_response_time;

    public function __construct(iterable $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
