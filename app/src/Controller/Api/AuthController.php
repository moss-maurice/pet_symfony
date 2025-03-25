<?php

namespace App\Controller\Api;

use App\Attribute\RequestBody;
use App\Service\Http\UserHttpService;
use App\Request\UserRegisterRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    public function __construct(
        readonly protected UserHttpService $service,
    ) {}

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(#[RequestBody] UserRegisterRequest $request): JsonResponse
    {
        return $this->service->registerUser($request);
    }

    #[Route('/logged', name: 'logged', methods: ['POST'])]
    public function logged(): JsonResponse
    {
        return $this->service->isLoggedUser();
    }
}
