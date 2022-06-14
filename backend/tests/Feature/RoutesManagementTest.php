<?php

namespace App\Tests\Feature;

use App\Factory\ApplicationFactory;
use App\Factory\UserFactory;
use App\Tests\WebTestCase;

class RoutesManagementTest extends WebTestCase
{
    /** @test */
    public function it_should_disable_routes_creation_when_unauthenticated()
    {
        // Given there is no user and you are not authenticated
        // And there is an application
        $app = ApplicationFactory::createOne();

        // When you try to create a ProxyRoute
        $this->client->jsonRequest('POST', '/api/applications/' . $app->getId() . '/routes', [
            'pattern' => '/mock/pattern',
            'clientPattern' => '/client/pattern'
        ]);

        // Then it throws an error
        static::assertResponseStatusCodeSame(401);
    }

    /** @test */
    public function it_should_disable_routes_creation_when_application_is_not_our()
    {
        // Given there is a user and you are authenticated
        $this->client->loginUser(UserFactory::createOne()->object());

        // And there is an application
        $app = ApplicationFactory::createOne();

        // When you try to create a ProxyRoute
        $this->client->jsonRequest('POST', '/api/applications/' . $app->getId() . '/routes', [
            'pattern' => '/mock/pattern',
            'clientPattern' => '/client/pattern'
        ]);

        // Then it throws an error
        static::assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function it_should_create_route_if_authenticated_and_application_is_our()
    {
        // Given there is a user and you are authenticated
        $user = $this->makeUser(true);

        // And there is an application
        $app = ApplicationFactory::createOne(['owner' => $user]);

        // When you try to create a ProxyRoute
        $this->client->jsonRequest('POST', '/api/applications/' . $app->getId() . '/routes', [
            'pattern' => '/mock/pattern',
            'isProtected' => true,
            "description" => ''
        ]);

        // Then it is successful
        static::assertResponseStatusCodeSame(201);
        // And a route is found !
        static::assertCount(1, $app->getRoutes());
        static::assertTrue($app->getRoutes()->first()->isProtected());
    }
}
