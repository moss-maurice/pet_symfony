<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AddProductRequest
{
    #[Assert\NotBlank(message: 'Product Id is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Product Id must be 1 or more')]
    private int $product;

    #[Assert\NotBlank(message: 'Amount is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Amount must be 1 or more')]
    private int $amount;

    public function getProduct(): int
    {
        return $this->product;
    }

    public function setProduct(int $product): self
    {
        $this->product = $product;

        return $this;
    }

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
