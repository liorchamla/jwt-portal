<?php

namespace App\Users\Storage;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasherListener
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function prePersist(User $user)
    {
        if (!$user->plainPassword) {
            return;
        }

        $user->setPassword($this->hasher->hashPassword($user, $user->plainPassword));
        $user->plainPassword = null;
    }
}
