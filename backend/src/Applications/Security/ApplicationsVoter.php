<?php

namespace App\Applications\Security;

use App\Entity\Application;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ApplicationsVoter extends Voter
{
    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === "CAN_CREATE_APPLICATION" && ($subject instanceof Application || $subject === null);
    }

    /**
     * @param Application $subject 
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$token->getUser()) {
            return false;
        }
        return true;
    }
}
