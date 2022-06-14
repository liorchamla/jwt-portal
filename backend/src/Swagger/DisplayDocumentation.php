<?php

namespace App\Swagger;

use App\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class DisplayDocumentation extends AbstractController
{

    #[Route("/swagger/{id}", name: "swagger_documentation_display")]
    public function __invoke(Application $application = null)
    {
        if (!$application) {
            throw new HttpException(404, "Application not found");
        }

        return $this->render('swagger.html.twig', [
            'json_url' => $this->generateUrl('swagger_documentation_generation', ['id' => $application->getId()])
        ]);
    }
}
