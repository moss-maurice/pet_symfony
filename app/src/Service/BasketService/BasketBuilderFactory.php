<?php

namespace App\Service\BasketService;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use App\Event\BasketOnAddedEvent;
use App\Event\BasketOnDeletedEvent;
use App\Event\BasketOnDropedEvent;
use App\Event\BasketOnUpdatedEvent;
use App\Service\BasketService\AbstractFactory;
use App\Service\BasketService\Interface\BasketFactoryInterface;

readonly final class BasketBuilderFactory extends AbstractFactory implements BasketFactoryInterface
{
    public function list(User $user): array
    {
        return $this->basketRepository->getBasketList($user);
    }

    public function get(User $user, int $id): ?Basket
    {
        return $this->basketRepository->getBasketItem($user, $id);
    }

    public function find(User $user, Product $product): ?Basket
    {
        return $this->basketRepository->findBasketItem($user, $product);
    }

    public function has(User $user, int $id): bool
    {
        return $this->basketRepository->hasBasketItem($user, $id);
    }

    public function canAdd(User $user): bool
    {
        return $this->basketRepository->getCarItemsCount($user) < $this->parameterBag->get('app.basketProductsLimit');
    }

    public function add(User $user, Product $product, int $amount): ?Basket
    {
        $basketItem = $this->basketRepository->addBasketItem($user, $product, $amount);

        if ($basketItem) {
            $this->eventDispatcher->dispatch(new BasketOnAddedEvent($basketItem), BasketOnAddedEvent::NAME);
        }

        return $basketItem;
    }

    public function update(User $user, Basket $basketItem, int $amount): ?Basket
    {
        $basketItem = $this->basketRepository->updateBasketItem($user, $basketItem, $amount);

        if ($basketItem) {
            $this->eventDispatcher->dispatch(new BasketOnUpdatedEvent($basketItem), BasketOnUpdatedEvent::NAME);
        }

        return $basketItem;
    }

    public function delete(User $user, Basket $basketItem): bool
    {
        if ($result = $this->basketRepository->deleteBasketItem($user, $basketItem)) {
            $this->eventDispatcher->dispatch(new BasketOnDeletedEvent($basketItem), BasketOnDeletedEvent::NAME);
        }

        return $result;
    }

    public function drop(User $user): bool
    {
        if ($result = $this->basketRepository->dropBasket($user)) {
            $this->eventDispatcher->dispatch(new BasketOnDropedEvent($user), BasketOnDropedEvent::NAME);
        }

        return $result;
    }
}
