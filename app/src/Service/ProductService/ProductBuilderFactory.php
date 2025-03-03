<?php

namespace App\Service\ProductService;

use App\Entity\Product;
use App\Event\ProductOnCreatedEvent;
use App\Service\ProductService\AbstractFactory;
use App\Service\ProductService\Interface\ProductFactoryInterface;

final class ProductBuilderFactory extends AbstractFactory implements ProductFactoryInterface
{
    public function build(string $json): Product
    {
        $product = $this->serializer->deserialize($json, Product::class, 'json');

        $product->getMeasurements()->setProduct($product);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new ProductOnCreatedEvent($product), ProductOnCreatedEvent::NAME);

        return $product;
    }

    public function list($page = 1, $limit = 10): array
    {
        return $this->productRepository->getProductsList($page, $limit);
    }

    public function count(): int
    {
        return $this->productRepository->getProductsCount();
    }

    public function has(int $id): bool
    {
        return $this->productRepository->hasProduct($id);
    }

    public function get(int $id): Product
    {
        return $this->productRepository->getProduct($id);
    }
}
