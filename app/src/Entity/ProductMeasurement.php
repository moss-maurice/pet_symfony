<?php

namespace App\Entity;

use App\Entity\Product;
use App\Repository\ProductMeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductMeasurementRepository::class)]
#[ORM\Table(name: '`products_measurements`')]
class ProductMeasurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['default'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?int $weight = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?int $length = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups(['default', 'catalog', 'generator', 'order'])]
    private ?int $height = null;

    #[ORM\OneToOne(inversedBy: 'measurements', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['parent'])]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
