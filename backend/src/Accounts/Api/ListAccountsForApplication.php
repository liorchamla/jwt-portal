<?php

namespace App\Accounts\Api;

use App\Entity\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class ListAccountsForApplication
{
    public function __construct(
        private Security $security,
        private SerializerInterface $serializer
    ) {
    }

    #[Route("/api/applications/{id}/accounts", name: "api_applications_accounts", methods: ["GET"])]
    public function __invoke(Application $application = null)
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new HttpException(401, "Unauthorized");
        }

        if (!$application || $application->getOwner() !== $user) {
            throw new HttpException(404, "Application not found");
        }

        return new JsonResponse(
            $this->serializer->serialize($application->getAccounts(), 'json',  ["groups" => "account:list"]),
            200,
            [],
            true
        );
    }
}
