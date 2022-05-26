<?php

namespace App\SubRequest;

use App\Repository\ProxyRouteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    public function __construct(private ProxyRouteRepository $proxyRouteRepository)
    {
    }

    public function isProtected(string $url, Request $request): bool
    {
        $subRequest = $this->createRequestForSubURL($url, $request);
        $context = $this->createRequestContext($subRequest);
        $routeCollection = $this->getRoutesCollection();

        $matcher = new UrlMatcher($routeCollection, $context);

        try {
            $matcher->matchRequest($subRequest);
            return true;
        } catch (ResourceNotFoundException $e) {
            return false;
        }
    }

    public function getRoutesCollection(): RouteCollection
    {
        $allRoutes = $this->proxyRouteRepository->findAll();


        $collection = new RouteCollection();

        foreach ($allRoutes as $proxyRoute) {
            $routeRequest = Request::create($proxyRoute->getPattern());
            $route = new Route($routeRequest->getPathInfo(), [], [], [], $routeRequest->getHost(), $routeRequest->getScheme());
            $collection->add($proxyRoute->getId(), $route);
        }

        return $collection;
    }

    public function createRequestForSubURL(string $subUrl, Request $currentRequest): Request
    {
        return Request::create($subUrl, $currentRequest->getMethod(), $currentRequest->request->all(), $currentRequest->cookies->all(), $currentRequest->files->all(), $currentRequest->server->all(), $currentRequest->getContent());
    }

    public function createRequestContext(Request $request): RequestContext
    {
        return (new RequestContext())->fromRequest($request);
    }
}
