<?php

namespace App\ProxyRequest\Firewall;

use App\Entity\Application;
use Exception;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    private function getRoutesCollection(Application $application)
    {
        $routes = new RouteCollection;

        foreach ($application->getRoutes() as $route) {
            $routes->add($route->getId(), new Route($route->getPattern()));
        }

        return $routes;
    }

    public function isProtected(string $url, Application $application)
    {
        $matcher = new UrlMatcher($this->getRoutesCollection($application), new RequestContext());

        try {
            $matcher->match($url);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
