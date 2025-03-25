<?php

namespace App\Service\Http;

use App\Service\OrderShipmentMethodService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class OrderShipmentMethodHttpService
{
    public function __construct(
        protected OrderShipmentMethodService $service,
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
