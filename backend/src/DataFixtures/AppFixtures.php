<?php

namespace App\DataFixtures;

use App\Factory\AccountFactory;
use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = UserFactory::createOne([
            'email' => 'user@mail.com',
            'plainPassword' => 'password'
        ]);

        $applications = ApplicationFactory::createMany(3, [
            'owner' => $user
        ]);

        foreach ($applications as $app) {
            ProxyRouteFactory::createMany(3, [
                'application' => $app
            ]);

            AccountFactory::createMany(3, [
                'application' => $app
            ]);
        }

        // $manager->flush();
    }
}
