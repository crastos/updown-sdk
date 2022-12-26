<?php

namespace Crastos\Updown\Http\Resources;

use Crastos\Updown\Http\Resources\Metrics\Requests;
use Crastos\Updown\Http\Resources\Metrics\Timings;

class Metrics extends Resource
{
    public readonly float $apdex_t;

    public readonly Requests $requests;

    public readonly Timings $timings;

    public function __construct(iterable|string|int $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
