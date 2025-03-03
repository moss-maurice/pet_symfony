<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderStatusRequest
{
    #[Assert\NotBlank(message: 'Order is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Order must be 1 or more')]
    private int $order;

    #[Assert\NotBlank(message: 'Status is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Status must be 1 or more')]
    private int $status;

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

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
