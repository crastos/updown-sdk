<?php

namespace Crastos\Updown\Http\Endpoints;

class StatusPages extends Endpoint
{
    use Concerns\Browseable;
    use Concerns\Editable;
    use Concerns\Createable;
    use Concerns\Deletable;

    protected string $resource_class = \Crastos\Updown\Http\Resources\StatusPage::class;
}
