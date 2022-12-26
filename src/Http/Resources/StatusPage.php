<?php

namespace Crastos\Updown\Http\Resources;

class StatusPage extends Resource
{
    public string $name;

    public string $description;

    /** @var ResourceRepository<Check[]> */
    public ResourceRepository $checks;

    public readonly string $token;

    public readonly string $url;

    public function __construct(iterable|string|int $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }
}
