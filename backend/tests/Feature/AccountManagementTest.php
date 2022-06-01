<?php

namespace App\Test\Feature;

use App\Factory\AccountFactory;
use App\Factory\ApplicationFactory;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class AccountManagementTest extends WebTestCase
{
    use Factories;

    /** @test */
    public function it_should_allow_consumers_to_create_accounts_on_an_application_level()
    {
        $client = $this->createClient();

        // Given we have an existing application
        $app = ApplicationFactory::createOne()->object();

        // When a consumer wants to create an account
        $client->jsonRequest('POST', '/a/' . $app->getId() . '/register', [
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // Then the response should be successful
        static::assertResponseStatusCodeSame(201);

        // Then we should be able to find this account
        $account = static::getContainer()->get(AccountRepository::class)->findOneBy(['email' => 'mock@mail.com']);
        static::assertNotNull($account);
        static::assertSame($app, $account->getApplication());
        static::assertNotEquals('password', $account->getPassword());
    }

    /** @test */
    public function it_should_disallow_consumers_to_create_accounts_on_an_unexisting_application()
    {
        $client = $this->createClient();

        // Given we have no application
        // When a consumer wants to create an account
        $client->jsonRequest('POST', '/a/666/register', [
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // Then the response should be successful
        static::assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function it_should_validate_account_data()
    {
        $client = $this->createClient();

        // Given we have an application
        $app = ApplicationFactory::createOne();

        // When a consumer wants to create an account
        $client->jsonRequest('POST', '/a/' . $app->getId() . '/register', [
            'email' => 'mockmail.com',
            'password' => ''
        ]);

        // Then the response should be successful
        static::assertResponseStatusCodeSame(400);
    }

    /** @test */
    public function it_should_allow_existing_account_to_login()
    {
        $client = $this->createClient();

        // Given we have an account
        $account = AccountFactory::createOne([
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // When a consumer wants to login
        $client->jsonRequest('POST', '/a/' . $account->getApplication()->getId() . '/login', [
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // Then the response should be successful
        static::assertResponseStatusCodeSame(200);

        // And the content should contain a JWT Token
        $json = json_decode($client->getResponse()->getContent());
        static::assertNotNull($json->token);
    }

    /** @test */
    public function it_should_deny_access_to_bad_credentials()
    {
        $client = $this->createClient();

        // Given we have an account
        $account = AccountFactory::createOne([
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // When a consumer wants to login with bad credentials
        $client->jsonRequest('POST', '/a/' . $account->getApplication()->getId() . '/login', [
            'email' => 'mockito@mail.com',
            'password' => 'password'
        ]);

        // Then the response should be successful
        static::assertResponseStatusCodeSame(401);

        // And the content should contain a JWT Token
        $json = json_decode($client->getResponse()->getContent());
        static::assertEquals("Bad credentials", $json->error);
    }

    /** @test */
    public function it_should_validate_credentials()
    {
        $client = $this->createClient();

        // Given we have an account
        $account = AccountFactory::createOne([
            'email' => 'mock@mail.com',
            'password' => 'password'
        ]);

        // When a consumer wants to login with bad credentials
        $client->jsonRequest('POST', '/a/' . $account->getApplication()->getId() . '/login', [
            'email' => 'mockmail.com',
            'password' => ''
        ]);

        // Then the response should not be successful
        static::assertResponseStatusCodeSame(400);
    }
}
