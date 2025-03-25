<?php

namespace App\Service\Http;

use App\Entity\User;
use App\Exception\BasketItemNotFoundException;
use App\Exception\BasketProductAlreadyExistsException;
use App\Exception\BasketProductNotFoundException;
use App\Exception\BasketProductsLimitReachedException;
use App\Exception\ProductNotFountException;
use App\Request\AddProductRequest;
use App\Request\UpdateProductAmountRequest;
use App\Service\BasketService;
use App\Service\ProductService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly final class BasketHttpService
{
    public function __construct(
        protected BasketService $service,
        protected ProductService $productService,
        protected ParameterBagInterface $parameterBag,
        protected SerializerInterface $serializer,
        protected EventDispatcherInterface $eventDispatcher
    ) {}

    public function list(User $user): JsonResponse
    {
        $list = $this->service->list($user);

        return new JsonResponse([
            'items' => $this->serializer->normalize($list, JsonEncoder::FORMAT, [
                'groups' => ['basket'],
            ]),
        ], JsonResponse::HTTP_OK);
    }

    public function addItem(User $user, AddProductRequest $request): JsonResponse
    {
        $product = $this->productService->item($request->getProduct());

        if (!$product) {
            throw new ProductNotFountException;
        }

        if (!$this->service->canAdd($user)) {
            throw new BasketProductsLimitReachedException;
        }

        if ($basketItem = $this->service->find($user, $product)) {
            throw new BasketProductAlreadyExistsException;
        }

        $basketItem = $this->service->add($user, $product, $request->getAmount());
        $this->service->execute();

        return new JsonResponse([
            'message' => "Basket item successfully added",
            'id' => $basketItem->getId(),
        ], JsonResponse::HTTP_OK);
    }

    public function drop(User $user): JsonResponse
    {
        $this->service->drop($user);
        $this->service->execute();

        return new JsonResponse([
            'message' => "Basket successfully droped",
        ], JsonResponse::HTTP_OK);
    }

    public function hasProduct(User $user, int $id): JsonResponse
    {
        $product = $this->productService->item($id);

        if (!$product) {
            throw new ProductNotFountException;
        }

        if (!($basketItem = $this->service->find($user, $product))) {
            throw new BasketProductNotFoundException;
        }

        return new JsonResponse([
            'message' => "Product found",
            'amount' => $basketItem->getAmount(),
        ], JsonResponse::HTTP_OK);
    }

    public function updateItem(User $user, int $id, UpdateProductAmountRequest $request): JsonResponse
    {
        if (!($basketItem = $this->service->item($user, $id))) {
            throw new BasketItemNotFoundException;
        }

        $this->service->update($user, $basketItem, $request->getAmount());
        $this->service->execute();

        return new JsonResponse([
            'message' => "Basket item successfully updated",
        ], JsonResponse::HTTP_OK);
    }

    public function deleteItem(User $user, int $id): JsonResponse
    {
        if (!($basketItem = $this->service->item($user, $id))) {
            throw new BasketItemNotFoundException;
        }

        $this->service->delete($user, $basketItem);
        $this->service->execute();

        return new JsonResponse([
            'message' => "Basket item successfully deleted",
        ], JsonResponse::HTTP_OK);
    }
}
