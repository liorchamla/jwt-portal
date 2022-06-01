<?php

namespace App\Accounts\Storage;

use App\Entity\Account;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordHasherListener
{

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function prePersist(Account $account)
    {
        $account->setPassword($this->hasher->hashPassword($account, $account->getPassword()));
    }
}
