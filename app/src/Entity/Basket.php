<?php

namespace App\Entity;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\BasketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
#[ORM\Table(name: '`baskets`')]
#[ORM\UniqueConstraint(name: 'unique_basket_user_product', columns: ['user_id', 'product_id'])]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default', 'basket'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'basket')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'basket')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default', 'basket'])]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(['default', 'basket'])]
    private ?int $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}