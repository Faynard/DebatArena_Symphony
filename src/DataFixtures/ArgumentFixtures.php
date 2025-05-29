<?php

namespace App\DataFixtures;

use App\Entity\Argument;
use App\Entity\Camp;
use App\Entity\Report;
use App\Entity\Sanction;
use App\Entity\User;
use App\Entity\Votes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArgumentFixtures extends Fixture implements DependentFixtureInterface
{

    private $faker;
    public function getDependencies(): array
    {
        return [
            CampFixtures::class,
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        $this->createArg($manager, "camp1", "1");
        $this->createArg($manager, "camp1", "2");
        $this->createArg($manager, "camp4", "1");
        $this->createArg($manager, "camp4", "2");
        $this->createArg($manager, "camp6", "1");
        $this->createArg($manager, "camp6", "2");


        $manager->flush();
    }

    public function createArg($manager, $campName, $campNumber){
        $camp = $this->getReference($campName.".".$campNumber, Camp::class);
        for($i = 0; $i < $this->faker->numberBetween(10, 20); $i++) {
            $user = $this->faker->numberBetween(1, 7);
            $argument = new Argument();
            $argument->setCamp($camp);
            $argument->setUser($this->getReference('user'.$user, User::class));
            $argument->setText($this->faker->text);
            $manager->persist($argument);

            /* Ajout de vote */
            for($k = 0; $k < $this->faker->numberBetween(0, 5); $k++) {
                $voteUser = $this->faker->numberBetween(1, 7);
                if($voteUser !== $user) {
                    $vote = new Votes();
                    $vote->setUser($this->getReference('user'.$voteUser, User::class));
                    $vote->setArgument($argument);
                    $manager->persist($vote);
                }
            }

            /* Ajout de report */
            for($k = 0; $k < $this->faker->numberBetween(0, 3); $k++) {
                $reportUser = $this->faker->numberBetween(1, 7);
                if($reportUser !== $user) {
                    $report = new Report();
                    $report->setUser($this->getReference('user'.$reportUser, User::class));
                    $report->setArgument($argument);
                    /* Ajout de sanction */
                    if($this->faker->numberBetween(1, 5) == 1) {
                        $report->setIsValid(true);
                        $sanction = new Sanction();
                        $sanction->setUser($this->getReference('moderator'.$this->faker->numberBetween(1,2), User::class));
                        $sanction->setArgument($argument);
                        $sanction->setReason($this->faker->text);
                        $manager->persist($sanction);
                    }
                    $manager->persist($report);
                }
            }

            /* Ajout de sous argument */
            if($user == 1 or $user == 2) {
                for($j = 0; $j < $this->faker->numberBetween(1, 5); $j++) {
                    $user = $this->faker->numberBetween(3, 7);
                    $campId = $this->faker->numberBetween(1, 2);
                    $subArgument = new Argument();
                    $subArgument->setCamp($this->getReference($campName.".".$campId, Camp::class));
                    $subArgument->setUser($this->getReference('user'.$user, User::class));
                    $subArgument->setMainArgument($argument);
                    $subArgument->setText($this->faker->text);
                    $manager->persist($subArgument);
                }
            }
        }
    }
}