<?php

namespace App\Response;

class ErrorResponse
{
    public function __construct(private string $message, private mixed $details = null)
    {
        // Do nothing!
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDetails(): mixed
    {
        return $this->details;
    }

}