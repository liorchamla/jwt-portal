<?php

namespace App\Test\Database;

use App\Entity\User;
use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Zenstruck\Foundry\Test\Factories;

class ApplicationsTest extends KernelTestCase
{
    use Factories;

    /** @test */
    public function it_should_handle_application_objects()
    {
        $application = ApplicationFactory::createOne();


        static::assertNotNull($application);
    }

    /** @test */
    public function it_should_handle_proxy_route_objects()
    {
        $proxyRoutes = ProxyRouteFactory::createMany(4, [
            'application' => ApplicationFactory::createOne()
        ]);

        static::assertCount(4, $proxyRoutes);
    }

    /** @test */
    public function it_should_link_application_with_authenticated_user()
    {
        // Given there is a user
        /** @var User */
        $user = UserFactory::createOne()->object();

        // And he is logged in 
        $token = new TestBrowserToken($user->getRoles(), $user, 'main');

        $container = $this->getContainer();
        $container->get('security.untracked_token_storage')->setToken($token);

        if ($container->has('session.factory')) {
            $session = $container->get('session.factory')->createSession();
            $session->set('_security_main', serialize($token));
            $session->save();
        }


        // When we create an application without providing an owner
        $application = ApplicationFactory::createOne([
            'owner' => null
        ]);

        // Then the application has automaticly the authenticated user as owner !
        static::assertEquals($user, $application->getOwner());
    }
}
