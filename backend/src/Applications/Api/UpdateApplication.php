<?php

namespace App\Applications\Api;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class UpdateApplication
{

    public function __construct(
        private Security $security,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private ValidatorInterface $validator
    ) {
    }

    #[Route("/api/applications/{slug}", name: "api_applications_update", methods: ["PUT"])]
    public function __invoke(Request $request, Application $application = null)
    {
        if (!$application) {
            throw new HttpException(404, "Application does not exist");
        }

        if (!$this->security->getUser()) {
            throw new HttpException(401, "Unauthorized");
        }

        /** @var Application */
        $updates = $this->serializer->deserialize($request->getContent(), Application::class, 'json');

        foreach ($application->getRoutes() as $route) {
            $this->em->remove($route);
        }

        $application->setTitle($updates->getTitle())
            ->setDescription($updates->getDescription());

        foreach ($updates->getRoutes() as $route) {
            $application->addRoute($route);
        }

        $this->em->flush();

        return new JsonResponse(
            $this->serializer->serialize($application, 'json', ["groups" => "application:read"]),
            200,
            [],
            true
        );
    }
}
