<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductMeasurement;
use App\Event\ProductOnCreatedEvent;
use App\Exception\InvalidJsonException;
use App\Repository\ProductRepository;
use App\Service\FakerService;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly final class ProductService
{
    readonly protected Generator $faker;

    public function __construct(
        protected ProductRepository $repository,
        protected FakerService $fakerService,
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer,
        protected EventDispatcherInterface $eventDispatcher
    ) {
        $this->faker = $fakerService->generator();
    }

    public function build(string $json): Product
    {
        $product = $this->serializer->deserialize($json, Product::class, 'json');

        $product->getMeasurements()->setProduct($product);

        $this->entityManager->persist($product);

        $this->eventDispatcher->dispatch(new ProductOnCreatedEvent($product), ProductOnCreatedEvent::NAME);

        return $product;
    }

    public function list($page = 1, $limit = 10): array
    {
        return $this->repository->getProductsList($page, $limit);
    }

    public function count(): int
    {
        return $this->repository->getProductsCount();
    }

    public function has(int $id): bool
    {
        return $this->repository->hasProduct($id);
    }

    public function item(int $id): Product
    {
        return $this->repository->getProduct($id);
    }

    public function createFromJson(string $json): Product
    {
        json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(json_last_error_msg());
        }

        return $this->build($json);
    }

    public function makeProduct(): Product
    {
        $version = Carbon::now()->timestamp;

        $product = new Product;

        $product->setName($this->faker->words(3, true));
        $product->setDescription($this->faker->paragraph(3));
        $product->setCost($this->faker->numberBetween(500, 50000));
        $product->setTax(ceil($product->getCost() * 0.13));
        $product->setVersion($version);

        $measurement = $this->makeProductMeasurement();

        if ($measurement) {
            $product->setMeasurements($measurement);
        }

        return $product;
    }

    public function makeProductMeasurement(): ProductMeasurement
    {
        $productMeasurement = new ProductMeasurement;

        $productMeasurement->setWeight($this->faker->numberBetween(1, 50));
        $productMeasurement->setLength($this->faker->numberBetween(10, 100));
        $productMeasurement->setWidth($this->faker->numberBetween(10, 100));
        $productMeasurement->setHeight($this->faker->numberBetween(10, 100));

        return $productMeasurement;
    }

    public function makeProducts(int $count): ArrayCollection
    {
        $products = new ArrayCollection;

        for ($i = 0; $i < $count; $i++) {
            $product = $this->makeProduct();

            $products->add($product);
        }

        return $products;
    }

    public function execute(): void
    {
        $this->entityManager->flush();
    }
}
