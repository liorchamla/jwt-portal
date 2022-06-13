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
        $url = '/' . $url;

        if (!$application) {
            throw new HttpException(404, "No application found");
        }

        $routeParams = $this->router->getRouteObject($url, $application);

        if (!$routeParams) {
            throw new HttpException(404, "Resource not found");
        }

        $proxyRoute = $routeParams['object'];

        if ($proxyRoute->isProtected() && !$this->auth->authenticate($request, $application)) {
            throw new HttpException(401, "JWT Not found or invalid");
        }

        $realUrl = $this->router->makeUrlWithParams($application, $proxyRoute, $routeParams);

        return $this->httpClient->makeApiRequest($realUrl, $request->getMethod());
    }
}
