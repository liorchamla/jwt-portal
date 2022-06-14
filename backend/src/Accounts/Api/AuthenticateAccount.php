<?php

namespace App\Accounts\Api;

use App\Accounts\Dto\Credentials;
use App\Entity\Application;
use App\Http\Exception\ConstraintsViolationsException;
use App\Http\JWT\Authentication;
use App\Repository\AccountRepository;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class AuthenticateAccount
{

    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private AccountRepository $accountRepository,
        private UserPasswordHasherInterface $hasher,
        private Authentication $auth
    ) {
    }

    #[Route("/a/{id}/login", name: "api_account_login", methods: ["POST"])]
    public function __invoke(Request $request, Application $application = null)
    {
        if (!$application) {
            throw new HttpException(404, "No application found");
        }

        $credentials = $this->serializer->deserialize($request->getContent(), Credentials::class, 'json');

        $errors = $this->validator->validate($credentials);

        if ($errors->count()) {
            throw new ConstraintsViolationsException($errors);
        }

        $account = $this->accountRepository->findOneBy(['email' => $credentials->email]);

        if (!$account) {
            throw new HttpException(401, "Bad credentials");
        }

        $isPasswordValid = $this->hasher->isPasswordValid($account, $credentials->password);

        if (!$isPasswordValid) {
            throw new HttpException(401, "Bad credentials");
        }

        $jwt = $this->auth->encode($account);

        return new JsonResponse(["token" => $jwt]);
    }
}
