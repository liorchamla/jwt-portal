<?php

namespace App\Tests;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Zenstruck\Foundry\Test\Factories;

class WebTestCase extends BaseWebTestCase
{
    use Factories;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function makeUser(bool $login = false, string $email = "mock@mail.com", string $password = "password")
    {
        $user = UserFactory::createOne([
            'email' => $email,
            'plainPassword' =>  $password
        ]);

        if ($login) {
            $this->client->loginUser($user->object());
        }

        return $user;
    }
}
