<?php

namespace App\Entity;

use App\Repository\OrderStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderStatusRepository::class)]
#[ORM\Table(name: '`orders_statuses`')]
#[ORM\UniqueConstraint(name: 'unique_order_status_name', columns: ['name'])]
class OrderStatus
{
    const DEFAULT_ID = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default', 'catalog', 'order'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default', 'catalog', 'order'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'status')]
    private Collection $orders;


    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrder(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setStatus($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getStatus() === $this) {
                $order->setStatus(null);
            }
        }

        return $this;
    }
}
