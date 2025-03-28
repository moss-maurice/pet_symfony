<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use App\Response\ErrorResponse;
use App\Response\ErrorValidationDetails;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

readonly class OnValidationExceptionListener
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if (!($throwable instanceof ValidationException)) {
            return;
        }

        $data = $this->serializer->serialize(
            new ErrorResponse($throwable->getMessage(), $this->formatViolations($throwable->getViolations())),
            JsonEncoder::FORMAT,
        );

        $event->setResponse(new JsonResponse($data, Response::HTTP_BAD_REQUEST, [], true));
    }

    private function formatViolations(ConstraintViolationListInterface $violations): ErrorValidationDetails
    {
        $details = new ErrorValidationDetails();

        foreach ($violations as $violation) {
            $details->addViolation($violation->getPropertyPath(), $violation->getMessage());
        }

        return $details;
    }
}
