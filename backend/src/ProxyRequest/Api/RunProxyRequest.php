<?php

namespace App\ProxyRequest\Api;

use App\Entity\Application;
use App\Http\JWT\Authentication;
use App\ProxyRequest\Firewall\Router;
use App\ProxyRequest\ProxyHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class RunProxyRequest
{

    public function __construct(
        private ProxyHttpClient $httpClient,
        private Router $router,
        private Authentication $auth
    ) {
    }

    #[Route("/a/{id}/u/{url}", name: "proxy_request_run", requirements: ['url' => '.+'])]
    public function __invoke(Request $request, Application $application = null, string $url = '')
    {
        if (!$application) {
            throw new HttpException(404, "No application found");
        }

        if ($this->router->isProtected($url, $application)) {
            // We have to check for JWT Token
            if (!$this->auth->authenticate($request, $application)) {
                throw new HttpException(401, "JWT Not found or invalid");
            }
        }

        return $this->httpClient->makeApiRequest($application->getBaseUrl() . $url, $request->getMethod());
    }
}
