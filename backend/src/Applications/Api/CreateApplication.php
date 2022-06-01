<?php

namespace App\Applications\Api;

use App\Entity\Application;
use App\Http\Exception\ConstraintsViolationsException;
use App\Repository\ApplicationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class CreateApplication
{
    public function __construct(private SerializerInterface $serializer, private ApplicationRepository $applicationRepository, private ValidatorInterface $validator, private Security $security)
    {
    }

    #[Route("/api/applications", name: "api_applications_create", methods: ["POST"])]
    #[IsGranted('CAN_CREATE_APPLICATION')]
    public function __invoke(Request $request)
    {
        $application = $this->serializer->deserialize($request->getContent(), Application::class, 'json');

        $errors = $this->validator->validate($application);

        if ($errors->count()) {
            throw new ConstraintsViolationsException($errors);
        }

        $this->applicationRepository->add($application, true);

        return new JsonResponse($this->serializer->serialize($application, 'json', ['groups' => ['application:read']]), 201, [], true);
    }
}
