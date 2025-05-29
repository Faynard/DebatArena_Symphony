<?php

namespace App\DataFixtures;

use App\Entity\Camp;
use App\Entity\Debate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [
            DebateFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $camp = new Camp();
        $camp->setDebate($this->getReference('debate1', Debate::class));
        $camp->setNameCamp("Pour la régulation");
        $manager->persist($camp);
        $this->addReference('camp1.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate1', Debate::class));
        $camp->setNameCamp("Contre la régulation");
        $manager->persist($camp);
        $this->addReference('camp1.2', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate2', Debate::class));
        $camp->setNameCamp("Oui");
        $manager->persist($camp);
        $this->addReference('camp2.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate2', Debate::class));
        $camp->setNameCamp("Non");
        $manager->persist($camp);
        $this->addReference('camp2.2', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate3', Debate::class));
        $camp->setNameCamp("Pour la suppression");
        $manager->persist($camp);
        $this->addReference('camp3.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate3', Debate::class));
        $camp->setNameCamp("Contre la suppression");
        $manager->persist($camp);
        $this->addReference('camp3.2', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate4', Debate::class));
        $camp->setNameCamp("Oui");
        $manager->persist($camp);
        $this->addReference('camp4.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate4', Debate::class));
        $camp->setNameCamp("Non");
        $manager->persist($camp);
        $this->addReference('camp4.2', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate5', Debate::class));
        $camp->setNameCamp("Oui largement");
        $manager->persist($camp);
        $this->addReference('camp5.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate5', Debate::class));
        $camp->setNameCamp("Non absolument pas");
        $manager->persist($camp);
        $this->addReference('camp5.2', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate6', Debate::class));
        $camp->setNameCamp("Pour l'autorisation");
        $manager->persist($camp);
        $this->addReference('camp6.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate6', Debate::class));
        $camp->setNameCamp("Contre l'autorisation");
        $manager->persist($camp);
        $this->addReference('camp6.2', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate7', Debate::class));
        $camp->setNameCamp("Oui");
        $manager->persist($camp);
        $this->addReference('camp7.1', $camp);

        $camp = new Camp();
        $camp->setDebate($this->getReference('debate7', Debate::class));
        $camp->setNameCamp("Non");
        $manager->persist($camp);
        $this->addReference('camp7.2', $camp);

        $manager->flush();
    }
}