<?php

namespace App\Tests\ProxyRequest\Firewall;

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
            'application' => $app->object(),
            'isProtected' => true
        ]);

        // Then our router can determine if '/mock/pattern/12' is protected
        /** @var Router */
        $router = self::getContainer()->get(Router::class);
        static::assertNotNull($router->getRouteObject('/mock/pattern/12/hello', $app->object()));
    }

    /** @test */
    public function it_should_know_if_a_pattern_does_not_belong_to_an_application()
    {
        // Given we have an application
        $app = ApplicationFactory::createOne();

        // And it has several route :
        ProxyRouteFactory::createMany(3, [
            'application' => $app->object(),
            'isProtected' => false
        ]);

        // Then our router can determine if '/mock/pattern/12' is protected
        /** @var Router */
        $router = self::getContainer()->get(Router::class);
        static::assertNull($router->getRouteObject('/mock/pattern/12/hello', $app->object()));
    }

    /** @test */
    public function it_should_know_if_a_pattern_is_protected_by_auth()
    {
        // Given we have an application
        $app = ApplicationFactory::createOne();

        // And it has several route :
        $route = ProxyRouteFactory::createOne([
            'pattern' => '/mock/pattern/{id}/{slug}',
            'application' => $app->object(),
            'isProtected' => true
        ]);

        // Then our router can determine if '/mock/pattern/12' is protected
        /** @var Router */
        $router = self::getContainer()->get(Router::class);
        static::assertTrue($router->isProtected('/mock/pattern/12/hello', $app->object()));
    }

    /** @test */
    public function it_should_recognise_params_and_generate_url_properly()
    {
        // Given we have an application with a route
        $app = ApplicationFactory::createOne();

        $route = ProxyRouteFactory::createOne([
            'application' => $app,
            'clientPattern' => '/mock/{age}/other/{id}',
            'pattern' => 'https://mockapi.io/{id}/how?param={age}'
        ]);

        // When we ask the router for the real URL
        /** @var Router */
        $router = self::getContainer()->get(Router::class);
        $realUrl = $router->makeUrlWithParams($app->object(), $route->object(), ['id' => "abc", 'age' => 35]);

        // Then it gives us the proper URL
        static::assertEquals('https://mockapi.io/abc/how?param=35', $realUrl);
    }
}
