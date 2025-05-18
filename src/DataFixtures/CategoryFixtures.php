<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /* Ces données ont été générées par IA */

        $category = new Category();
        $category->setNameCategory("Technologie & Innovation");
        $category->setDescriptionCategory("Analyse l’impact des nouvelles technologies sur nos vies, notre travail et notre futur.");
        $manager->persist($category);
        $this->addReference("technologie-innovation", $category);

        $category = new Category();
        $category->setNameCategory("Société & Culture");
        $category->setDescriptionCategory("Traite des normes sociales, des différences culturelles, et des dynamiques sociales contemporaines.");
        $manager->persist($category);
        $this->addReference("societe-culture", $category);

        $category = new Category();
        $category->setNameCategory("Économie & Travail");
        $category->setDescriptionCategory("Examine les systèmes économiques, le marché du travail, et les politiques de redistribution.");
        $manager->persist($category);
        $this->addReference("economie-travail", $category);

        $category = new Category();
        $category->setNameCategory("Éducation & Connaissance");
        $category->setDescriptionCategory("Se penche sur les méthodes d’enseignement, les programmes scolaires et l’accès à la connaissance.");
        $manager->persist($category);
        $this->addReference("education-connaissance", $category);

        $manager->flush();
    }
}