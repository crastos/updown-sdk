<?php

namespace Crastos\Updown\Enums;

enum HttpVerb: string
{
    case GET = 'GET/HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
}
