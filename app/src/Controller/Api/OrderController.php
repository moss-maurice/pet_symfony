<?php

namespace App\Controller\Api;

use App\Attribute\RequestBody;
use App\Entity\User;
use App\Request\CreateOrderRequest;
use App\Request\PaginationRequest;
use App\Request\UpdateOrderRequest;
use App\Service\OrderService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/order', name: 'api_order_')]
#[IsGranted('ROLE_USER')]
class OrderController extends AbstractController
{
    protected ?User $user;

    public function __construct(
        readonly protected OrderService $orderService,
        readonly protected UserService $userService
    ) {
        $this->user = $this->userService->factory()->loggedUser();
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(#[RequestBody] PaginationRequest $request): JsonResponse
    {
        return $this->orderService->getOrderList($this->user, $request);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function create(#[RequestBody] CreateOrderRequest $request): JsonResponse
    {
        return $this->orderService->createOrder($this->user, $request);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->orderService->getOrder($this->user, $id);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, UpdateOrderRequest $request): JsonResponse
    {
        return $this->orderService->updateOrder($this->user, $id, $request);
    }

    #[Route('/shipment-methods', name: 'shipment_methods', methods: ['GET'])]
    public function shipmentMethods(): JsonResponse
    {
        return $this->orderService->getShipmentMethodsList();
    }

    #[Route('/statuses', name: 'statuses', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function statuses(): JsonResponse
    {
        return $this->orderService->getStatusesList();
    }
}
