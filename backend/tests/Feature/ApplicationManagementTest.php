<?php

namespace App\Tests\Feature;

use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\Repository\ApplicationRepository;
use App\Tests\WebTestCase;

class ApplicationManagementTest extends WebTestCase
{

    /** @test */
    public function it_should_deny_application_creation_if_unauthenticated()
    {
        // Given we have no user
        // When we try to create an application
        $this->client->jsonRequest('POST', '/api/applications', [
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
        // Given there is one user
        // And he is logged in
        $this->makeUser(true);


        // When we try to create an application
        $this->client->jsonRequest('POST', '/api/applications', [
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
        $repository = $this->client->getContainer()->get(ApplicationRepository::class);
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
        // Given there is one user
        // And he is logged in
        $this->makeUser(true);

        // When we try to create an application
        $this->client->jsonRequest('POST', '/api/applications', [
            'description' => '',
            'baseUrl' => 'mock_fail'
        ]);

        // And we should receive a good status
        static::assertResponseStatusCodeSame(400);
    }

    /** @test */
    public function it_should_list_applications_for_current_user()
    {
        // Given we have a current user
        // And he has some applications
        $user = $this->makeUser(true);

        ApplicationFactory::createMany(5, [
            'owner' => $user->object()
        ]);

        // When we call /api/applications in GET
        $this->client->jsonRequest("GET", "/api/applications");

        // Then we should receive applications
        $data = json_decode($this->client->getResponse()->getContent());

        static::assertResponseIsSuccessful();
        static::assertIsArray($data);
        static::assertCount(5, $data);
    }

    /** @test */
    public function it_should_get_an_application_with_id()
    {

        // Given we have a current user
        // And he has some applications
        $user = $this->makeUser(true);

        $application = ApplicationFactory::createOne([
            'owner' => $user->object()
        ]);

        // When we call /api/applications in GET
        $this->client->jsonRequest("GET", "/api/applications/" . $application->getId());

        // Then we should receive applications
        json_decode($this->client->getResponse()->getContent());

        static::assertResponseIsSuccessful();
    }

    /** @test */
    public function it_should_allow_application_modifications()
    {
        // Given we have a user and an application

        $user = $this->makeUser(true);

        $application = ApplicationFactory::createOne([
            'owner' => $user->object(),
        ]);

        ProxyRouteFactory::createMany(4, [
            'application' => $application->object()
        ]);

        // When we call /api/applications/{id} in PUT
        $this->client->jsonRequest("PUT", "/api/applications/" . $application->getId(), [
            "title" => "MOCK_TITLE",
            "description" => "MOCK_DESCRIPTION",
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
    }

    /** @test */
    public function it_should_allow_application_delete()
    {
        // Given we have a user and an application
        $user = $this->makeUser(true);
        $application = ApplicationFactory::createOne([
            'owner' => $user->object(),
        ]);

        $id = $application->getId();

        // When we call /api/applications/{id} in PUT
        $this->client->jsonRequest("DELETE", "/api/applications/" . $application->getId());

        // Then the application and routes should have been updated
        static::assertResponseIsSuccessful();
        static::assertCount(0, ApplicationFactory::findBy(['id' => $id]));
    }
}
