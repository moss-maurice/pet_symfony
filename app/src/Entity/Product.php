<?php

namespace App\Entity;

use App\Entity\Basket;
use App\Entity\ProductMeasurement;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`products`')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default', 'catalog', 'basket', 'order'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['default', 'catalog', 'generator', 'basket', 'order'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'basket'])]
    private ?int $cost = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'basket'])]
    private ?int $tax = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?int $version = null;

    #[ORM\OneToOne(mappedBy: 'product', cascade: ['persist', 'remove'])]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?ProductMeasurement $measurements = null;

    /**
     * @var Collection<int, Basket>
     */
    #[ORM\OneToMany(targetEntity: Basket::class, mappedBy: 'product_id')]
    private Collection $basket;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'product')]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->basket = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
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
        $this->name = ucfirst(trim($name));

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = trim($description);

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getTax(): ?int
    {
        return $this->tax;
    }

    public function setTax(int $tax): static
    {
        $this->tax = $tax;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getMeasurements(): ?ProductMeasurement
    {
        return $this->measurements;
    }

    public function setMeasurements(ProductMeasurement $measurements): static
    {
        // set the owning side of the relation if necessary
        if ($measurements->getProduct() !== $this) {
            $measurements->getProduct($this);
        }

        $this->measurements = $measurements;

        return $this;
    }

    /**
     * @return Collection<int, Basket>
     */
    public function getBasket(): Collection
    {
        return $this->basket;
    }

    public function addBasket(Basket $basket): static
    {
        if (!$this->basket->contains($basket)) {
            $this->basket->add($basket);
            $basket->setProduct($this->id);
        }

        return $this;
    }

    public function removeBasket(Basket $basket): static
    {
        if ($this->basket->removeElement($basket)) {
            // set the owning side to null (unless already changed)
            if ($basket->getProduct() === $this) {
                $basket->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                $orderProduct->setProduct(null);
            }
        }

        return $this;
    }
}
