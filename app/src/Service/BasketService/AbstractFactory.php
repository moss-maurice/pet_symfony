<?php

namespace App\Service\BasketService;

use App\Repository\BasketRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class AbstractFactory
{
    public function __construct(
        readonly protected BasketRepository $basketRepository,
        readonly protected ParameterBagInterface $parameterBag,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {}
}
