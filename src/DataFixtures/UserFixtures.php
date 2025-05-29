<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        /* Ces données ont été générées par IA */
        $user = new User();
        $user->setEmail("pixelrider91@mail.com");
        $user->setPseudo("PixelRider");
        $user->setPassword("pixel123");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_ADMIN", "ROLE_MODERATOR"]);
        $manager->persist($user);
        $this->addReference("admin", $user);

        $user = new User();
        $user->setEmail("lunascript42@webmail.net");
        $user->setPseudo("LunaScript");
        $user->setPassword("luna2024");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_MODERATOR"]);
        $manager->persist($user);
        $this->addReference("moderator1", $user);

        $user = new User();
        $user->setEmail("frostnova77@neomail.org");
        $user->setPseudo("FrostNova");
        $user->setPassword("frostpass");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_MODERATOR"]);
        $manager->persist($user);
        $this->addReference("moderator2", $user);

        $user = new User();
        $user->setEmail("codeknight33@devmail.io");
        $user->setPseudo("CodeKnight");
        $user->setPassword("knight33");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user1", $user);

        $user = new User();
        $user->setEmail("zencrawler18@mailnet.com");
        $user->setPseudo("ZenCrawler");
        $user->setPassword("zenzen18");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user2", $user);

        $user = new User();
        $user->setEmail("blaze.echo@fastmail.net");
        $user->setPseudo("BlazeEcho");
        $user->setPassword("blaze123");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user3", $user);

        $user = new User();
        $user->setEmail("neospectre07@cybermail.com");
        $user->setPseudo("NeoSpectre");
        $user->setPassword("neo007");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user4", $user);

        $user = new User();
        $user->setEmail("velvetbyte99@mailhub.org");
        $user->setPseudo("VelvetByte");
        $user->setPassword("bytebyte");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user5", $user);

        $user = new User();
        $user->setEmail("darklynx24@netzone.io");
        $user->setPseudo("DarkLynx");
        $user->setPassword("lynx24");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user6", $user);

        $user = new User();
        $user->setEmail("astromancer11@outlook.net");
        $user->setPseudo("AstroMancer");
        $user->setPassword("astro11");
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);
        $this->addReference("user7", $user);

        $manager->flush();
    }
}