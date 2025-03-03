<?php

namespace App\Service;

use App\Entity\Product;
use App\Exception\InvalidJsonException;
use App\Exception\ProductNotFountException;
use App\Repository\ProductRepository;
use App\Request\PaginationRequest;
use App\Service\ProductService\ProductBuilderFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class ProductService
{
    protected string $data;
    protected ProductBuilderFactory $factory;

    public function __construct(
        readonly protected ProductRepository $productRepository,
        readonly protected EntityManagerInterface $entityManager,
        readonly protected SerializerInterface $serializer,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = new ProductBuilderFactory($this->productRepository, $this->entityManager, $this->serializer, $this->eventDispatcher);
    }

    public function factory()
    {
        return $this->factory;
    }

    public function getProductsList(PaginationRequest $request): JsonResponse
    {
        $products = $this->factory->list($request->getPage(), $request->getLimit());

        return new JsonResponse([
            'items' => $this->serializer->normalize($products, JsonEncoder::FORMAT, [
                'groups' => ['catalog'],
            ]),
            'total' => $this->factory->count(),
            'page' => $request->getPage(),
            'limit' => $request->getLimit(),
        ], JsonResponse::HTTP_OK);
    }

    public function getProduct(int $id): JsonResponse
    {
        if (!$this->factory->has($id)) {
            throw new ProductNotFountException;
        }

        $product = $this->factory->get($id);

        return new JsonResponse($this->serializer->normalize($product, JsonEncoder::FORMAT, [
            'groups' => ['catalog'],
        ]), JsonResponse::HTTP_OK);
    }

    public function createProductFromJson(string $json): Product
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(json_last_error_msg());
        }

        return $this->factory->build($json);
    }
}
