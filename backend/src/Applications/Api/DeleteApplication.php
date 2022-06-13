<?php

namespace App\Applications\Api;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class DeleteApplication
{
    public function __construct(
        private Security $security,
        private SerializerInterface $serializer,
        private ApplicationRepository $applicationRepository
    ) {
    }

    #[Route("/api/applications/{id}", name: "api_applications_delete", methods: ["DELETE"])]
    public function __invoke(Application $app = null)
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new HttpException(401, "Unauthenticated");
        }

        if (!$app || $app->getOwner() !== $user) {
            throw new HttpException(404, "Application not found !");
        }

        $this->applicationRepository->remove($app, true);

        return new JsonResponse(
            $this->serializer->serialize($app, 'json', ['groups' => ['application:read']]),
            204,
            [],
            true
        );
    }
}
