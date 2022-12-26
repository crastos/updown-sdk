<?php

namespace Crastos\Updown\Http\Resources;

class Recipient extends Resource
{
    public string $id;

    public string $type;

    public string $value;

    public bool $selected = true;

    public readonly string $name;

    public readonly bool $immutable;

    public function __construct(iterable|string|int $payload = [])
    {
        parent::__construct($payload);

        foreach ($this->payload as $key => $value) {
            $this->{$key} = $this->transform($key, $value);
        }
    }

    public static function foreignKey()
    {
        return 'id';
    }
}
