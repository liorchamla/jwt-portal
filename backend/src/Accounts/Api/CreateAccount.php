<?php

namespace App\Accounts\Api;


use App\Entity\Account;
use App\Entity\Application;
use App\Http\Exception\ConstraintsViolationsException;
use App\Repository\AccountRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class CreateAccount
{
    public function __construct(
        private SerializerInterface $serializer,
        private AccountRepository $accountRepository,
        private ValidatorInterface $validator
    ) {
    }

    #[Route("/a/{id}/register", name: "api_account_register", methods: ["POST"])]
    public function __invoke(Request $request, Application $application = null)
    {
        if (!$application) {
            throw new HttpException(404, "No application found");
        }

        $account = $this->serializer->deserialize($request->getContent(), Account::class, 'json');
        $account->setApplication($application);

        $errors = $this->validator->validate($account);

        if ($errors->count()) {
            throw new ConstraintsViolationsException($errors);
        }

        $this->accountRepository->add($account, true);

        return new JsonResponse(
            $this->serializer->serialize($account, 'json', ['groups' => 'account:read']),
            201,
            [],
            true
        );
    }
}
