<?php

namespace App\Controller;

use App\Entity\ProxyRoute;
use App\Form\ProxyRouteType;
use App\Repository\ProxyRouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/routes')]
class ProxyRouteController extends AbstractController
{
    #[Route('/', name: 'app_proxy_route_index', methods: ['GET'])]
    public function index(ProxyRouteRepository $proxyRouteRepository): Response
    {
        return $this->render('proxy_route/index.html.twig', [
            'proxy_routes' => $proxyRouteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_proxy_route_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProxyRouteRepository $proxyRouteRepository): Response
    {
        $proxyRoute = new ProxyRoute();
        $form = $this->createForm(ProxyRouteType::class, $proxyRoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proxyRouteRepository->add($proxyRoute, true);

            return $this->redirectToRoute('app_proxy_route_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proxy_route/new.html.twig', [
            'proxy_route' => $proxyRoute,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proxy_route_show', methods: ['GET'])]
    public function show(ProxyRoute $proxyRoute): Response
    {
        return $this->render('proxy_route/show.html.twig', [
            'proxy_route' => $proxyRoute,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proxy_route_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProxyRoute $proxyRoute, ProxyRouteRepository $proxyRouteRepository): Response
    {
        $form = $this->createForm(ProxyRouteType::class, $proxyRoute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proxyRouteRepository->add($proxyRoute, true);

            return $this->redirectToRoute('app_proxy_route_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proxy_route/edit.html.twig', [
            'proxy_route' => $proxyRoute,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proxy_route_delete', methods: ['POST'])]
    public function delete(Request $request, ProxyRoute $proxyRoute, ProxyRouteRepository $proxyRouteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $proxyRoute->getId(), $request->request->get('_token'))) {
            $proxyRouteRepository->remove($proxyRoute, true);
        }

        return $this->redirectToRoute('app_proxy_route_index', [], Response::HTTP_SEE_OTHER);
    }
}
