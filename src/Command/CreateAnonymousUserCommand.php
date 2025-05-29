<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-anonymous-user',
    description: 'Crée l\'utilisateur anonyme avec ID 0.'
)]
class CreateAnonymousUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userRepository = $this->em->getRepository(User::class);
        $existing = $userRepository->find(0);

        if ($existing) {
            $output->writeln('<comment>Un utilisateur avec l\'ID 0 existe déjà.</comment>');
            return Command::SUCCESS;
        }

        $user = new User();
        
        // Forcer l'ID 0 : attention, nécessite un override temporaire
        $metadata = $this->em->getClassMetadata(User::class);
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $user->setId(0);

        $user->setEmail('anonyme@domain.local');
        $user->setPseudo('Anonyme');

        // Le mot de passe est égal au pseudo : "Anonyme"
        $hashed = $this->hasher->hashPassword($user, 'Anonyme');
        $user->setPassword($hashed);

        $user->setCreatedDate(new \DateTimeImmutable());
        $user->setIsBanned(false);
        $user->setRoles(['ROLE_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('<info>Utilisateur "Anonyme" créé avec succès (ID 0).</info>');
        return Command::SUCCESS;
    }
}
