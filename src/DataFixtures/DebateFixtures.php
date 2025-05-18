<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Debate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DebateFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /* Ces données ont été générées par IA */
        $technologie = $this->getReference("technologie-innovation", Category::class);
        $societe = $this->getReference("societe-culture", Category::class);
        $economie = $this->getReference("economie-travail", Category::class);
        $education = $this->getReference("education-connaissance", Category::class);

        $debate = new Debate();
        $debate->setNameDebate("L’IA doit-elle être régulée ?");
        $debate->setDescriptionDebate("L’intelligence artificielle évolue vite, mais faut-il poser des règles dès maintenant ?");
        $debate->setIsValid(true);
        $debate->setCreationDate(new \DateTime('2024-03-10'));
        $debate->setUserCreated($this->getReference("user1", User::class));
        $debate->addCategory($technologie);
        $debate->addCategory($education);
        $manager->persist($debate);
        $this->setReference("debate1", $debate);

        $debate = new Debate();
        $debate->setNameDebate("Le revenu universel est-il la solution à la pauvreté ?");
        $debate->setDescriptionDebate("Analyse des avantages et inconvénients d’un revenu garanti.");
        $debate->setIsValid(false);
        $debate->setCreationDate(new \DateTime('2023-11-25'));
        $debate->setUserCreated($this->getReference("user1", User::class));
        $debate->addCategory($societe);
        $debate->addCategory($economie);
        $manager->persist($debate);
        $this->setReference("debate2", $debate);

        $debate = new Debate();
        $debate->setNameDebate("Faut-il supprimer les devoirs à la maison ?");
        $debate->setDescriptionDebate("L’efficacité des devoirs est-elle prouvée ? Et leur impact sur l’égalité ?");
        $debate->setIsValid(false);
        $debate->setCreationDate(new \DateTime('2024-01-05'));
        $debate->setUserCreated($this->getReference("user1", User::class));
        $debate->addCategory($education);
        $manager->persist($debate);
        $this->setReference("debate3", $debate);

        $debate = new Debate();
        $debate->setNameDebate("Les voitures thermiques doivent-elles être interdites dès 2035 ?");
        $debate->setDescriptionDebate("Un débat sur l’urgence climatique et les moyens d’action possibles.");
        $debate->setIsValid(true);
        $debate->setCreationDate(new \DateTime('2024-05-01'));
        $debate->setUserCreated($this->getReference("user2", User::class));
        $debate->addCategory($technologie);
        $debate->addCategory($societe);
        $manager->persist($debate);
        $this->setReference("debate4", $debate);

        $debate = new Debate();
        $debate->setNameDebate("La démocratie directe est-elle plus efficace ?");
        $debate->setDescriptionDebate("Les citoyens doivent-ils décider eux-mêmes via référendums ?");
        $debate->setIsValid(false);
        $debate->setCreationDate(new \DateTime('2024-04-10'));
        $debate->setUserCreated($this->getReference("user3", User::class));
        $debate->addCategory($societe);
        $manager->persist($debate);
        $this->setReference("debate5", $debate);

        $debate = new Debate();
        $debate->setNameDebate("Faut-il autoriser les manipulations génétiques ?");
        $debate->setDescriptionDebate("Les progrès scientifiques justifient-ils de modifier le vivant ?");
        $debate->setIsValid(true);
        $debate->setCreationDate(new \DateTime('2024-01-18'));
        $debate->setUserCreated($this->getReference("moderator1", User::class));
        $debate->addCategory($technologie);
        $debate->addCategory($societe);
        $manager->persist($debate);
        $this->setReference("debate6", $debate);

        $debate = new Debate();
        $debate->setNameDebate("Faut-il interdire les fake news sur les réseaux sociaux ?");
        $debate->setDescriptionDebate("Censure ou protection de l’information : où placer la limite ?");
        $debate->setIsValid(false);
        $debate->setCreationDate(new \DateTime('2024-05-10'));
        $debate->setUserCreated($this->getReference("user2", User::class));
        $debate->addCategory($societe);
        $debate->addCategory($education);
        $manager->persist($debate);
        $this->setReference("debate7", $debate);

        $manager->flush();
    }
}