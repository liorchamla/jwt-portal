<?php

namespace App\Test\ProxyReques\Firewall;

use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\ProxyRequest\Firewall\Router;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class RouterTest extends KernelTestCase
{
    use Factories;

    /** @test */
    public function it_should_know_if_a_pattern_belongs_to_an_application()
    {
        // Given we have an application
        $app = ApplicationFactory::createOne();

        // And it has a route :
        $route = ProxyRouteFactory::createOne([
            'pattern' => '/mock/pattern/{id}/{slug}',
            'application' => $app->object()
        ]);

        // Then our router can determine if '/mock/pattern/12' is protected
        /** @var Router */
        $router = self::getContainer()->get(Router::class);
        static::assertTrue($router->isProtected('/mock/pattern/12/hello', $app->object()));
    }

    /** @test */
    public function it_should_know_if_a_pattern_does_not_belong_to_an_application()
    {
        // Given we have an application
        $app = ApplicationFactory::createOne();

        // And it has several route :
        ProxyRouteFactory::createMany(3, [
            'application' => $app->object()
        ]);

        // Then our router can determine if '/mock/pattern/12' is protected
        /** @var Router */
        $router = self::getContainer()->get(Router::class);
        static::assertFalse($router->isProtected('/mock/pattern/12/hello', $app->object()));
    }
}
