<?php

// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\OrderShipmentMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderShipmentMethodFixtures extends Fixture
{
    protected array $data = [
        'Курьером',
        'Самовывоз',
    ];

    public function load(ObjectManager $manager): void
    {
        if (is_array($this->data) and !empty($this->data)) {
            foreach ($this->data as $name) {
                $entity = new OrderShipmentMethod();

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
