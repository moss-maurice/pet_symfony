<?php

namespace App\Response;

class ErrorWoutDetailsResponse
{
    public function __construct(private string $message)
    {
        // Do nothing!
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
