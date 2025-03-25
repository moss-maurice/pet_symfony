<?php

namespace App\Service\Http;

use App\Service\OrderStatusService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class OrderStatusHttpService
{
    public function __construct(
        protected OrderStatusService $service,
        protected SerializerInterface $serializer
    ) {}

    public function list(): JsonResponse
    {
        $list = $this->service->list();

        return new JsonResponse([
            'items' => $this->serializer->normalize($list, JsonEncoder::FORMAT, [
                'groups' => ['catalog'],
            ]),
        ], JsonResponse::HTTP_OK);
    }
}
