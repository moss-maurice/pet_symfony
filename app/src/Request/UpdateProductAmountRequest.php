<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductAmountRequest
{
    #[Assert\NotBlank(message: 'Amount is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Amount must be 1 or more')]
    private int $amount;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
