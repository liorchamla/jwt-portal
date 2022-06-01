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
            'baseUrl' => 'https://mockurl.io'
        ]);

        // Then the application should exist
        /** @var ApplicationRepository */
        $repository = $client->getContainer()->get(ApplicationRepository::class);
        static::assertNotNull($repository->findOneBy(['title' => 'MOCK_APPLICATION']));

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
}
