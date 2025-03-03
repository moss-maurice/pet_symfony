<?php

namespace App\Response;

class ErrorDebugDetails
{
    public function __construct(private string $trace)
    {
        // Do nothing!
    }

    public function getTrace(): string
    {
        return $this->trace;
    }

}