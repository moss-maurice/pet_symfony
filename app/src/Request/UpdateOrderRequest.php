<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderRequest
{
    #[Assert\NotBlank(message: 'Status is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Status must be 1 or more')]
    private int $status;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
