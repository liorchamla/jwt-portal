<?php

namespace App\Users\Api;

use App\Entity\User;
use App\Http\Exception\ConstraintsViolationsException;
use App\Repository\UserRepository;
use App\Users\Dto\Registration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class Register
{

    public function __construct(
        private UserRepository $userRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route("/api/register", name: "api_users_register", methods: ["POST"])]
    public function __invoke(Request $request)
    {
        /** @var Registration */
        $registration = $this->serializer->deserialize($request->getContent(), Registration::class, 'json');

        $errors = $this->validateRegistration($registration);

        if ($errors->count()) {
            throw new ConstraintsViolationsException($errors);
        }

        $user = $registration->toEntity();

        $this->userRepository->add($user, true);

        return new JsonResponse(
            $this->serializer->serialize($user, 'json'),
            201,
            [],
            true
        );
    }

    private function validateRegistration(Registration $registration)
    {
        $errors = $this->validator->validate($registration);

        if ($this->userRepository->findOneBy(['email' => $registration->email])) {
            $errors->add(new ConstraintViolation("Email $registration->email is already used", "", [], "", "email", $registration->email));
        }

        return $errors;
    }
}
