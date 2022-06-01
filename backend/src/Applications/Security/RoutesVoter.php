<?php

namespace App\Applications\Security;

use App\Entity\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class RoutesVoter extends Voter
{
    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === "CAN_CREATE_ROUTE" && ($subject instanceof Application || $subject === null);
    }

    /**
     * @param Application $subject 
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$token->getUser()) {
            return false;
        }

        if ($subject->getOwner() !== $token->getUser()) {
            throw new HttpException(404, "Application not found");
        }

        return true;
    }
}
