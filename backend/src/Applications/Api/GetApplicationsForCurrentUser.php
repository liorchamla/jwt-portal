<?php

namespace App\Applications\Api;

use App\Entity\User;
use App\Repository\ApplicationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class GetApplicationsForCurrentUser
{
    public function __construct(
        private Security $security,
        private ApplicationRepository $applicationRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route("/api/applications", methods: ["GET"])]
    public function __invoke()
    {
        /** @var User */
        $user = $this->security->getUser();

        if (!$user) {
            throw new HttpException(401, "You have to be authenticated to ask for applications");
        }

        $apps = $this->applicationRepository->findBy(['owner' => $user]);

        return new JsonResponse(
            $this->serializer->serialize($apps, 'json', ['groups' => ['application:read']]),
            200,
            [],
            true
        );
    }
}
