<?php

namespace App\Controller;

use App\SubRequest\HttpClient;
use App\SubRequest\Router;
use App\SubRequest\Firewall;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubController extends AbstractController
{

    public function __construct(private Router $router, private HttpClient $httpClient, private Firewall $firewall)
    {
    }

    #[Route('/sub', name: 'sub_get_url')]
    public function getApiData(Request $request): Response
    {
        $url = $request->get('u');

        if (!$this->router->isProtected($url, $request)) {
            return $this->httpClient->makeApiRequest($url, $request->getMethod(), $request->getContent());
        }

        try {
            $this->firewall->authenticate($request);
            return $this->httpClient->makeApiRequest($url, $request->getMethod(), $request->getContent());
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 401);
        }
    }
}
