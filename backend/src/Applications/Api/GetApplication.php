<?php

namespace App\Applications\Api;

use App\Entity\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class GetApplication
{
    public function __construct(
        private Security $security,
        private SerializerInterface $serializer
    ) {
    }

    #[Route("/api/applications/{id}", name: "api_applications_get", methods: ["GET"])]
    public function __invoke(Application $app = null)
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new HttpException(401, "Unauthenticated");
        }

        if (!$app || $app->getOwner() !== $user) {
            throw new HttpException(404, "Application not found !");
        }

        return new JsonResponse(
            $this->serializer->serialize($app, 'json', ['groups' => ['application:read']]),
            200,
            [],
            true
        );
    }
}
