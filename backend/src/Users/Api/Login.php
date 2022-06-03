<?php

namespace App\Users\Api;

use App\Repository\UserRepository;
use App\Users\Dto\Registration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class Login
{


    #[Route("/api/login", name: "api_users_login")]
    public function __invoke(Request $request)
    {
    }
}
