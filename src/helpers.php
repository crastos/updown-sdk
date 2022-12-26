<?php

namespace Crastos\Updown;

use Crastos\Updown\Http\Client;

function updown($secret)
{
    return new Updown(new Client($secret));
}
