<?php

namespace App\Tests\Feature;

use App\Factory\AccountFactory;
use App\Factory\ApplicationFactory;
use App\Factory\UserFactory;
use App\Tests\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class UserManagementTest extends WebTestCase
{
    /** @test */
    public function it_should_allow_users_to_register()
    {
        // Given there are no users

        // When we call /api/register
        $this->client->jsonRequest("POST", "/api/register", [
            "email" => "mock@mail.com",
            "password" => "password"
        ]);

        // Then our account is created
        static::assertResponseStatusCodeSame(201);
        $users = UserFactory::findBy(["email" => "mock@mail.com"]);
        static::assertCount(1, $users);
    }

    /** @test */
    public function it_should_validate_registration_data()
    {
        // Given there are no users

        // When we call /api/register
        $this->client->jsonRequest("POST", "/api/register", [
            "email" => "mockmail.com",
            "password" => ""
        ]);

        // Then our account is created
        static::assertResponseStatusCodeSame(400);
    }

    /** @test */
    public function it_should_validate_email_uniqueness()
    {
        // Given there is a user
        $user = $this->makeUser(false, "mock@mail.com", "MOCK_PASSWORD");

        // When we call /api/register with the same email
        $this->client->jsonRequest("POST", "/api/register", [
            "email" => "mock@mail.com",
            "password" => "password"
        ]);

        // Then the registration is refused
        static::assertResponseStatusCodeSame(400);
    }

    /** @test */
    public function it_should_login_user_with_good_credentials()
    {

        // Given we have a user
        $user = $this->makeUser();

        ApplicationFactory::createMany(4, [
            "owner" => $user->object()
        ]);


        // When we try to login
        $this->client->jsonRequest('POST', '/api/login', [
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // Then we should receive a success
        static::assertResponseIsSuccessful();
        // And it should contain a JWT
        static::assertNotNull(json_decode($this->client->getResponse()->getContent())->token);
    }
}
