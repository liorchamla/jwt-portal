<?php

namespace App\Applications\Api;

use App\Entity\Application;
use App\Entity\ProxyRoute;
use App\Http\Exception\ConstraintsViolationsException;
use App\Repository\ApplicationRepository;
use App\Repository\ProxyRouteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class CreateRoute
{
    public function __construct(
        private SerializerInterface $serializer,
        private ProxyRouteRepository $proxyRouteRepository,
        private ApplicationRepository $applicationRepository,
        private ValidatorInterface $validator,
        private Security $security
    ) {
    }

    #[Route("/api/applications/{appId}/routes", methods: ["POST"], name: "api_routes_create")]
    #[ParamConverter('application', class: Application::class, options: ['id' => 'appId'])]
    #[IsGranted('CAN_CREATE_ROUTE', subject: 'application')]
    public function __invoke(Request $request, Application $application = null)
    {
        /** @var ProxyRoute */
        $route = $this->serializer->deserialize($request->getContent(), ProxyRoute::class, 'json');
        $route->setApplication($application);

        $errors = $this->validator->validate($route);

        if ($errors->count()) {
            throw new ConstraintsViolationsException($errors);
        }

        $this->proxyRouteRepository->add($route, true);

        return new JsonResponse($this->serializer->serialize($route, 'json', ['groups' => ['route:read']]), 201, [], true);
    }
}
