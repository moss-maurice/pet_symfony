<?php

namespace App\Service\ProductService;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly abstract class AbstractFactory
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface $serializer,
        protected EventDispatcherInterface $eventDispatcher
    ) {}
}
