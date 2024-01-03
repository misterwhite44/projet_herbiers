<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Lieu;

class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $lieu1 = new Lieu();
        $lieu1->setNom('Roscanvel Z1');

        $lieu2 = new Lieu();
        $lieu2->setNom('Roscanvel Z2');

        $manager->persist($lieu1);
        $manager->persist($lieu2);

        $manager->flush();
    }
}
