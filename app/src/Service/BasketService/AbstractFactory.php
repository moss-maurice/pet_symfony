<?php

namespace App\Service\BasketService;

use App\Repository\BasketRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly abstract class AbstractFactory
{
    public function __construct(
        protected BasketRepository $basketRepository,
        protected ParameterBagInterface $parameterBag,
        protected EventDispatcherInterface $eventDispatcher
    ) {}
}
