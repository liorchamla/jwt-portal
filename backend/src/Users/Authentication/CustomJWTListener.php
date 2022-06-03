<?php

namespace App\Users\Authentication;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class CustomJWTListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var User */
        $user = $event->getUser();

        $payload = $event->getData();

        $payload['applications'] = [];

        foreach ($user->getApplications() as $app) {
            $payload['applications'][] =  [
                "id" => $app->getId(),
                "title" => $app->getTitle(),
                "description" => $app->getDescription()
            ];
        }

        $event->setData($payload);
    }
}
