<?php

namespace App\Test\Feature;

use App\Factory\AccountFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class UserManagementTest extends WebTestCase
{
    use Factories;

    /** @test */
    public function it_should_allow_users_to_register()
    {
        $client = static::createClient();
        // Given there are no users

        // When we call /api/register
        $client->jsonRequest("POST", "/api/register", [
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
        $client = static::createClient();
        // Given there are no users

        // When we call /api/register
        $client->jsonRequest("POST", "/api/register", [
            "email" => "mockmail.com",
            "password" => ""
        ]);

        // Then our account is created
        static::assertResponseStatusCodeSame(400);
    }

    /** @test */
    public function it_should_validate_email_uniqueness()
    {
        $client = static::createClient();
        // Given there is a user
        UserFactory::createOne([
            'email' => 'mock@mail.com'
        ]);

        // When we call /api/register with the same email
        $client->jsonRequest("POST", "/api/register", [
            "email" => "mock@mail.com",
            "password" => "password"
        ]);

        // Then the registration is refused
        static::assertResponseStatusCodeSame(400);
    }
}
