<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use App\Event\BasketOnAddedEvent;
use App\Event\BasketOnDeletedEvent;
use App\Event\BasketOnDropedEvent;
use App\Event\BasketOnUpdatedEvent;
use App\Repository\BasketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly final class BasketService
{
    public function __construct(
        protected BasketRepository $repository,
        protected ParameterBagInterface $parameterBag,
        protected EntityManagerInterface $entityManager,
        protected EventDispatcherInterface $eventDispatcher
    ) {}

    public function list(User $user): array
    {
        return $this->repository->getBasketList($user);
    }

    public function item(User $user, int $id): ?Basket
    {
        return $this->repository->getBasketItem($user, $id);
    }

    public function find(User $user, Product $product): ?Basket
    {
        return $this->repository->findBasketItem($user, $product);
    }

    public function has(User $user, int $id): bool
    {
        return $this->repository->hasBasketItem($user, $id);
    }

    public function canAdd(User $user): bool
    {
        return $this->repository->getCarItemsCount($user) < $this->parameterBag->get('app.basketProductsLimit');
    }

    public function add(User $user, Product $product, int $amount): ?Basket
    {
        $basketItem = $this->addBasketItem($user, $product, $amount);

        if ($basketItem) {
            $this->eventDispatcher->dispatch(new BasketOnAddedEvent($basketItem), BasketOnAddedEvent::NAME);
        }

        return $basketItem;
    }

    public function update(User $user, Basket $basketItem, int $amount): ?Basket
    {
        $basketItem = $this->updateBasketItem($user, $basketItem, $amount);

        if ($basketItem) {
            $this->eventDispatcher->dispatch(new BasketOnUpdatedEvent($basketItem), BasketOnUpdatedEvent::NAME);
        }

        return $basketItem;
    }

    public function delete(User $user, Basket $basketItem): bool
    {
        if ($result = $this->deleteBasketItem($user, $basketItem)) {
            $this->eventDispatcher->dispatch(new BasketOnDeletedEvent($basketItem), BasketOnDeletedEvent::NAME);
        }

        return $result;
    }

    public function drop(User $user): bool
    {
        if ($result = $this->dropBasket($user)) {
            $this->eventDispatcher->dispatch(new BasketOnDropedEvent($user), BasketOnDropedEvent::NAME);
        }

        return $result;
    }

    protected function addBasketItem(User $user, Product $product, int $amount): Basket
    {
        $basketItem = new Basket();

        $basketItem->setUser($user);
        $basketItem->setProduct($product);
        $basketItem->setAmount($amount);

        $this->entityManager->persist($basketItem);

        return $basketItem;
    }

    protected function updateBasketItem(User $user, Basket $basketItem, int $amount): ?Basket
    {
        if ($basketItem->getUser() !== $user) {
            return null;
        }

        $basketItem->setAmount($amount);

        $this->entityManager->persist($basketItem);

        return $basketItem;
    }

    protected function deleteBasketItem(User $user, Basket $basketItem): bool
    {
        if ($basketItem->getUser() !== $user) {
            return false;
        }

        $id = $basketItem->getId();

        $this->entityManager->remove($basketItem);

        return !$this->repository->hasBasketItem($user, $id);
    }

    protected function dropBasket(User $user): bool
    {
        $list = $this->repository->getBasketList($user);

        if (is_array($list) and !empty($list)) {
            foreach ($list as $basketItem) {
                $this->entityManager->remove($basketItem);
            }
        }

        return $this->repository->getCarItemsCount($user) > 0 ? false : true;
    }

    public function execute(): void
    {
        $this->entityManager->flush();
    }
}
