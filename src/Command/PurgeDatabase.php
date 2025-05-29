<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:purge',
    description: 'Purger la base de données'
)]
class PurgeDatabase extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->entityManager = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Lancement de la purge<info>');

        $connection = $this->entityManager->getConnection();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');

        $output->writeln('<info>Purge réussie</info>');
        return Command::SUCCESS;
    }
}
