<?php

namespace Crastos\Updown\Concerns;

trait GeneratesClassNames
{
    protected function generateClassName(string $path): string
    {
        return implode('', array_map('ucfirst', explode('_', trim(implode('\\', array_map('ucfirst', explode('/', $path))), '\\'))));
    }
}
