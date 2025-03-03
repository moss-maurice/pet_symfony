<?php

namespace App\Controller\Api;

use App\Attribute\RequestBody;
use App\Request\UserRegisterRequest;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    public function __construct(
        readonly protected UserService $userService,
    ) {}

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(#[RequestBody] UserRegisterRequest $request): JsonResponse
    {
        return $this->userService->registerUser($request);
    }

    #[Route('/logged', name: 'logged', methods: ['POST'])]
    public function logged(): JsonResponse
    {
        return $this->userService->isLoggedUser();
    }
}
