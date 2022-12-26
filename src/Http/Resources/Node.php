<?php

namespace Crastos\Updown\Http\Resources;

class Node extends Resource
{
    public readonly string $name;

    public readonly string $ip;

    public readonly string $ip6;

    public readonly string $city;

    public readonly string $country;

    public readonly string $country_code;

    public readonly float $lat;

    public readonly float $lng;

    public function __construct(iterable|string|int $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
