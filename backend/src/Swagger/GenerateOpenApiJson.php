<?php

namespace App\Swagger;

use App\Entity\Application;
use App\Swagger\Transform\OpenAPI;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class GenerateOpenApiJson
{
    public function __construct(private OpenAPI $transformer)
    {
    }

    #[Route("/swagger/{id}/generate", name: "swagger_documentation_generation")]
    public function __invoke(Application $application = null)
    {
        $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];

        if (!$application) {
            throw new NotFoundHttpException();
        }

        $json = $this->transformer->transformToJson($application, $baseUrl);

        return new JsonResponse($json, 200, [], true);
    }
}
