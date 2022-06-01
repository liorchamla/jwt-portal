<?php

namespace App\Test\Database\UserManagement;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class PasswordHashingTest extends KernelTestCase
{

    use Factories;

    /** @test */
    public function it_should_hash_user_plain_password_before_persist()
    {
        $user = UserFactory::createOne();

        static::assertEmpty($user->plainPassword);
        static::assertNotEmpty($user->getPassword());
    }
}
