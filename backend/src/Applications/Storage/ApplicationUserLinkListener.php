<?php

namespace App\Applications\Storage;

use App\Entity\Application;
use LogicException;
use Symfony\Component\Security\Core\Security;

class ApplicationUserLinkListener
{

    public function __construct(private Security $security)
    {
    }

    public function prePersist(Application $application)
    {
        if ($application->hasOwner()) {
            return;
        }

        if ($this->security->getUser() === null) {
            throw new LogicException("Can't store an application without being authenticated");
        }

        $application->setOwner($this->security->getUser());
    }
}
