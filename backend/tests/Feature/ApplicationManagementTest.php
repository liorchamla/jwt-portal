<?php

namespace App\Test\Feature;

use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\Factory\UserFactory;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class ApplicationManagementTest extends WebTestCase
{
    use Factories;

    /** @test */
    public function it_should_deny_application_creation_if_unauthenticated()
    {
        // Given we have no user

        // When we try to create an application
        $client = $this->createClient();

        $client->jsonRequest('POST', '/api/applications', [
            'title' => 'MOCK_APPLICATION',
            'description' => 'MOCK_DESCRIPTION',
            'baseUrl' => 'https://mockurl.io'
        ]);

        // Then we should have an error
        static::assertResponseStatusCodeSame(401);
    }

    /** @test */
    public function it_should_allow_application_creation_if_authenticated()
    {
        $client = $this->createClient();

        // Given there is one user
        $user = UserFactory::createOne([
            'email' => 'mock@mail.com',
            'password' => 'mock_password'
        ]);

        // And he is logged in
        $client->loginUser($user->object());

        // When we try to create an application
        $client->jsonRequest('POST', '/api/applications', [
            'title' => 'MOCK_APPLICATION',
            'description' => 'MOCK_DESCRIPTION',
            'baseUrl' => 'https://mockurl.io',
            'routes' => [
                [
                    'pattern' => '/clients/{id}',
                    'clientPattern' => '/customers/{id}'
                ],
                [
                    'pattern' => '/clients',
                    'clientPattern' => '/customers'
                ],
            ]
        ]);

        // Then the application should exist
        /** @var ApplicationRepository */
        $repository = $client->getContainer()->get(ApplicationRepository::class);
        $application = $repository->findOneBy(['title' => 'MOCK_APPLICATION']);
        static::assertNotNull($application);

        static::assertCount(2, $application->getRoutes());

        // And we should receive a good status
        static::assertResponseIsSuccessful();
        static::assertResponseStatusCodeSame(201);
    }

    /** @test */
    public function it_should_validate_application_data()
    {
        $client = $this->createClient();

        // Given there is one user
        $user = UserFactory::createOne([
            'email' => 'mock@mail.com',
            'password' => 'mock_password'
        ]);

        // And he is logged in
        $client->loginUser($user->object());

        // When we try to create an application
        $client->jsonRequest('POST', '/api/applications', [
            'description' => '',
            'baseUrl' => 'mock_fail'
        ]);

        // And we should receive a good status
        static::assertResponseStatusCodeSame(400);
    }

    /** @test */
    public function it_should_list_applications_for_current_user()
    {
        $client = static::createClient();

        // Given we have a current user
        // And he has some applications
        $user = UserFactory::createOne();
        $applications = ApplicationFactory::createMany(5, [
            'owner' => $user->object()
        ]);

        $client->loginUser($user->object());

        // When we call /api/applications in GET
        $client->jsonRequest("GET", "/api/applications");

        // Then we should receive applications
        $data = json_decode($client->getResponse()->getContent());

        static::assertResponseIsSuccessful();
        static::assertIsArray($data);
        static::assertCount(5, $data);
    }

    /** @test */
    public function it_should_allow_application_modifications()
    {
        // Given we have a user and an application
        $client = static::createClient();

        $user = UserFactory::createOne();
        $application = ApplicationFactory::createOne([
            'owner' => $user->object(),
        ]);
        $routes = ProxyRouteFactory::createMany(4, [
            'application' => $application->object()
        ]);

        $client->loginUser($user->object());

        // When we call /api/applications/{id} in PUT
        $client->jsonRequest("PUT", "/api/applications/" . $application->getId(), [
            "title" => "MOCK_TITLE",
            "description" => "MOCK_DESCRIPTION",
            "baseUrl" => "MOCK_URL",
            "routes" => [
                [
                    "pattern" => "MOCK_PATTERN",
                    "clientPattern" => "MOCK_CLIENT_PATTERN"
                ]
            ]
        ]);

        // Then the application and routes should have been updated
        static::assertCount(1, $application->getRoutes());
        static::assertEquals("MOCK_TITLE", $application->getTitle());
        static::assertEquals("MOCK_DESCRIPTION", $application->getDescription());
        static::assertEquals("MOCK_URL", $application->getBaseUrl());
    }
}
