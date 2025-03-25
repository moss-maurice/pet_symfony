<?php

namespace App\Service\Http;

use App\Exception\ProductNotFountException;
use App\Request\PaginationRequest;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class ProductHttpService
{
    public function __construct(
        protected ProductService $service,
        protected SerializerInterface $serializer
    ) {}

    public function list(PaginationRequest $request): JsonResponse
    {
        $products = $this->service->list($request->getPage(), $request->getLimit());

        return new JsonResponse([
            'items' => $this->serializer->normalize($products, JsonEncoder::FORMAT, [
                'groups' => ['catalog'],
            ]),
            'total' => $this->service->count(),
            'page' => $request->getPage(),
            'limit' => $request->getLimit(),
        ], JsonResponse::HTTP_OK);
    }

    public function item(int $id): JsonResponse
    {
        if (!$this->service->has($id)) {
            throw new ProductNotFountException;
        }

        $product = $this->service->item($id);

        return new JsonResponse($this->serializer->normalize($product, JsonEncoder::FORMAT, [
            'groups' => ['catalog'],
        ]), JsonResponse::HTTP_OK);
    }
}
