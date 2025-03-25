<?php

namespace App\Controller\Api;

use App\Attribute\RequestBody;
use App\Request\PaginationRequest;
use App\Service\Http\ProductHttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product', name: 'api_product_')]
class ProductController extends AbstractController
{
    public function __construct(
        readonly protected ProductHttpService $service,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(#[RequestBody] PaginationRequest $request): JsonResponse
    {
        return $this->service->list($request);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->service->item($id);
    }
}
