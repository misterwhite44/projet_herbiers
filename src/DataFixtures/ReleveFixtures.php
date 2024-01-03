<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Releve;
use App\Entity\Lieu;

class ReleveFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $lieuRepository = $manager->getRepository(Lieu::class);
        $lieu1 = $lieuRepository->findOneBy(['nom' => 'Roscanvel Z1']);
        $lieu2 = $lieuRepository->findOneBy(['nom' => 'Roscanvel Z2']);

        $releve1 = new Releve();
        $releve1->setDate(new \DateTime('2020-01-03'));
        $releve1->setLieu($lieu1);
        $releve1->setReleveBrut('3/3/3/9/6/6/1/9/4');

        $releve2 = new Releve();
        $releve2->setDate(new \DateTime('2020-01-04'));
        $releve2->setLieu($lieu2);
        $releve2->setReleveBrut('1/3/2/5/3/3/2/1/5');

        $manager->persist($releve1);
        $manager->persist($releve2);

        $manager->flush();
    }
}
