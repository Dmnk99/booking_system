<?php

namespace App\Controller;

use App\Entity\ServiceProvider;
use App\Form\ServiceProviderType;
use App\Repository\ServiceProviderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/provider')]
class ServiceProviderController extends AbstractController
{
    #[Route('/', name: 'app_service_provider_index', methods: ['GET'])]
    public function index(ServiceProviderRepository $serviceProviderRepository): Response
    {
        return $this->render('service_provider/index.html.twig', [
            'service_providers' => $serviceProviderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_provider_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceProvider = new ServiceProvider();
        $form = $this->createForm(ServiceProviderType::class, $serviceProvider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceProvider);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_provider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_provider/new.html.twig', [
            'service_provider' => $serviceProvider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_provider_show', methods: ['GET'])]
    public function show(ServiceProvider $serviceProvider): Response
    {
        return $this->render('service_provider/show.html.twig', [
            'service_provider' => $serviceProvider,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_provider_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceProvider $serviceProvider, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceProviderType::class, $serviceProvider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_provider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_provider/edit.html.twig', [
            'service_provider' => $serviceProvider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_provider_delete', methods: ['POST'])]
    public function delete(Request $request, ServiceProvider $serviceProvider, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serviceProvider->getId(), $request->request->get('_token'))) {
            $entityManager->remove($serviceProvider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_provider_index', [], Response::HTTP_SEE_OTHER);
    }
}
