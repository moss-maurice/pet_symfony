<?php

namespace App\Controller\Api;

use App\Attribute\RequestBody;
use App\Entity\User;
use App\Request\CreateOrderRequest;
use App\Request\PaginationRequest;
use App\Request\UpdateOrderRequest;
use App\Service\Http\OrderHttpService;
use App\Service\Http\OrderShipmentMethodHttpService;
use App\Service\Http\OrderStatusHttpService;
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
        readonly protected OrderHttpService $orderService,
        readonly protected UserService $userService,
        readonly protected OrderShipmentMethodHttpService $orderShipmentMethodService,
        readonly protected OrderStatusHttpService $orderStatusService
    ) {
        $this->user = $this->userService->loggedUser();
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(#[RequestBody] PaginationRequest $request): JsonResponse
    {
        return $this->orderService->list($this->user, $request);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function create(#[RequestBody] CreateOrderRequest $request): JsonResponse
    {
        return $this->orderService->create($this->user, $request);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->orderService->item($this->user, $id);
    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(int $id, UpdateOrderRequest $request): JsonResponse
    {
        return $this->orderService->update($this->user, $id, $request);
    }

    #[Route('/shipment-methods', name: 'shipment_methods', methods: ['GET'])]
    public function shipmentMethods(): JsonResponse
    {
        return $this->orderShipmentMethodService->list();
    }

    #[Route('/statuses', name: 'statuses', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function statuses(): JsonResponse
    {
        return $this->orderStatusService->list();
    }
}
