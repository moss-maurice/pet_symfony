<?php

namespace App\Controller\Api;

use App\Attribute\RequestBody;
use App\Entity\User;
use App\Request\AddProductRequest;
use App\Request\UpdateProductAmountRequest;
use App\Service\BasketService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/basket', name: 'api_basket_')]
#[IsGranted('ROLE_USER')]
class BasketController extends AbstractController
{
    protected ?User $user;

    public function __construct(
        readonly protected BasketService $basketService,
        readonly protected UserService $userService
    ) {
        $this->user = $this->userService->factory()->loggedUser();
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->basketService->getBasketList($this->user);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function create(#[RequestBody] AddProductRequest $request): JsonResponse
    {
        return $this->basketService->addBasketItem($this->user, $request);
    }

    #[Route('', name: 'drop', methods: ['DELETE'])]
    public function drop(): JsonResponse
    {
        return $this->basketService->dropBasket($this->user);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(#[RequestBody] UpdateProductAmountRequest $request, int $id): JsonResponse
    {
        return $this->basketService->updateBasketItem($this->user, $id, $request);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        return $this->basketService->deleteBasketItem($this->user, $id);
    }

    #[Route('/has-product/{id}', name: 'has_product', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function hasProduct(int $id): JsonResponse
    {
        return $this->basketService->hasBasketProduct($this->user, $id);
    }
}
