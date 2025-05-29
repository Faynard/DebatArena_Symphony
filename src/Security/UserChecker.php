<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isBanned()) {
            //  Empêche la connexion et affiche ce message sur la page de login
            throw new CustomUserMessageAccountStatusException('Votre compte a été banni.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Laisse vide sauf si besoin de logique post-auth
    }
}