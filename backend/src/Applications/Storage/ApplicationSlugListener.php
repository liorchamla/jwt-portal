<?php

namespace App\Applications\Storage;

use App\Entity\Application;
use Symfony\Component\String\Slugger\SluggerInterface;

class ApplicationSlugListener
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function prePersist(Application $application)
    {
        if (empty($application->getSlug())) {
            $application->setSlug(
                strtolower($this->slugger->slug($application->getTitle()))
            );
        }
    }
}
