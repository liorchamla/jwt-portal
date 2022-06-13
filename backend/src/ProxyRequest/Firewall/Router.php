<?php

namespace App\ProxyRequest\Firewall;

use App\Entity\Application;
use App\Entity\ProxyRoute;
use Exception;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    private function getRoutesCollection(Application $application, bool $forClientPattern = true)
    {
        $routes = new RouteCollection;

        foreach ($application->getRoutes() as $route) {
            $pattern = $forClientPattern ? $route->getClientPattern() : $route->getPattern();

            $routes->add($route->getId(), new Route($pattern, ["object" => $route]));
        }

        return $routes;
    }

    public function isProtected(string $url, Application $application)
    {
        $routeParams = $this->getRouteObject($url, $application);

        if (!$routeParams) {
            return false;
        }

        /** @var ProxyRoute */
        $proxyRoute = $routeParams['object'];

        return $proxyRoute->isProtected();
    }

    public function getRouteObject(string $url, Application $application): ?array
    {
        $matcher = new UrlMatcher($this->getRoutesCollection($application), new RequestContext());

        try {
            return $matcher->match($url);
        } catch (Exception $e) {
            return null;
        }
    }

    public function makeUrlWithParams(Application $application, ProxyRoute $proxyRoute, array $params = [])
    {
        $generator = new UrlGenerator($this->getRoutesCollection($application, false), new RequestContext());

        $realUrl = $generator->generate($proxyRoute->getId(), array_filter($params, fn ($key) => $key !== "_route", ARRAY_FILTER_USE_KEY));

        return urldecode(substr($realUrl, 1));
    }
}
