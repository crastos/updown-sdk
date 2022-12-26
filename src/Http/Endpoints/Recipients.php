<?php

namespace Crastos\Updown\Http\Endpoints;

class Recipients extends Endpoint
{
    use Concerns\Browseable;
    use Concerns\Createable;
    use Concerns\Deletable;

    protected string $resource_class = \Crastos\Updown\Http\Resources\Recipient::class;
}
