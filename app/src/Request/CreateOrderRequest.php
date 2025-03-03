<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderRequest
{
    #[Assert\NotBlank(message: 'Phone is required')]
    #[Assert\Regex(pattern: '/^\+[0-9]{11}$/', message: 'Phone number must be char "+" and 11 digits')]
    private string $phone;

    #[Assert\NotBlank(message: 'Shipment method is required')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'Shipment method must be 1 or more')]
    private int $shipmentMethod;

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getShipmentMethod(): int
    {
        return $this->shipmentMethod;
    }

    public function setShipmentMethod(int $shipmentMethod): self
    {
        $this->shipmentMethod = $shipmentMethod;

        return $this;
    }
}
