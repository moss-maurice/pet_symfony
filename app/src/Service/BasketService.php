<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\BasketItemNotFoundException;
use App\Exception\BasketProductAlreadyExistsException;
use App\Exception\BasketProductNotFoundException;
use App\Exception\BasketProductsLimitReachedException;
use App\Exception\ProductNotFountException;
use App\Repository\BasketRepository;
use App\Request\AddProductRequest;
use App\Request\UpdateProductAmountRequest;
use App\Service\BasketService\BasketBuilderFactory;
use App\Service\ProductService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class BasketService
{
    protected BasketBuilderFactory $factory;

    public function __construct(
        readonly protected BasketRepository $basketRepository,
        readonly protected ProductService $productService,
        readonly protected ParameterBagInterface $parameterBag,
        readonly protected SerializerInterface $serializer,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = new BasketBuilderFactory($this->basketRepository, $this->parameterBag, $this->eventDispatcher);
    }

    public function factory()
    {
        return $this->factory;
    }

    public function getBasketList(User $user): JsonResponse
    {
        $basket = $this->factory->list($user);

        return new JsonResponse([
            'items' => $this->serializer->normalize($basket, JsonEncoder::FORMAT, [
                'groups' => ['basket'],
            ]),
        ], JsonResponse::HTTP_OK);
    }

    public function addBasketItem(User $user, AddProductRequest $request): JsonResponse
    {
        $product = $this->productService->factory()->get($request->getProduct());

        if (!$product) {
            throw new ProductNotFountException;
        }

        if (!$this->factory->canAdd($user)) {
            throw new BasketProductsLimitReachedException;
        }

        if ($basketItem = $this->factory->find($user, $product)) {
            throw new BasketProductAlreadyExistsException;
        }

        $basketItem = $this->factory->add($user, $product, $request->getAmount());

        return new JsonResponse([
            'message' => "Basket item successfully added",
            'id' => $basketItem->getId(),
        ], JsonResponse::HTTP_OK);
    }

    public function dropBasket(User $user): JsonResponse
    {
        $this->factory->drop($user);

        return new JsonResponse([
            'message' => "Basket successfully droped",
        ], JsonResponse::HTTP_OK);
    }

    public function hasBasketProduct(User $user, int $id): JsonResponse
    {
        $product = $this->productService->factory()->get($id);

        if (!$product) {
            throw new ProductNotFountException;
        }

        if (!($basketItem = $this->factory->find($user, $product))) {
            throw new BasketProductNotFoundException;
        }

        return new JsonResponse([
            'message' => "Product found",
            'amount' => $basketItem->getAmount(),
        ], JsonResponse::HTTP_OK);
    }

    public function updateBasketItem(User $user, int $id, UpdateProductAmountRequest $request): JsonResponse
    {
        if (!($basketItem = $this->factory->get($user, $id))) {
            throw new BasketItemNotFoundException;
        }

        $this->factory->update($user, $basketItem, $request->getAmount());

        return new JsonResponse([
            'message' => "Basket item successfully updated",
        ], JsonResponse::HTTP_OK);
    }

    public function deleteBasketItem(User $user, int $id): JsonResponse
    {
        if (!($basketItem = $this->factory->get($user, $id))) {
            throw new BasketItemNotFoundException;
        }

        $this->factory->delete($user, $basketItem);

        return new JsonResponse([
            'message' => "Basket item successfully deleyed",
        ], JsonResponse::HTTP_OK);
    }
}
