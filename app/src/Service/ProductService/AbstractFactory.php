<?php

namespace App\Service\ProductService;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class AbstractFactory
{
    public function __construct(
        readonly protected ProductRepository $productRepository,
        readonly protected EntityManagerInterface $entityManager,
        readonly protected SerializerInterface $serializer,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {}
}
