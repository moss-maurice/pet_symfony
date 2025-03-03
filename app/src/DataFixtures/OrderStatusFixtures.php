<?php

// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderStatusFixtures extends Fixture
{
    protected array $data = [
        'Оплачен и ждёт сборки',
        'В сборке',
        'Готов к выдаче',
        'Доставляется',
        'Получен',
        'Отменен',
    ];

    public function load(ObjectManager $manager): void
    {
        if (is_array($this->data) and !empty($this->data)) {
            foreach ($this->data as $name) {
                $entity = new OrderStatus();

                $entity->setName($name);

                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            //UserFixtures::class,
        ];
    }
}
