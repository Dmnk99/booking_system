<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfileType;
use App\Repository\ServiceRepository;
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser(); // Získanie aktuálne prihláseného používateľa

        // Načítanie iba služieb, ktoré vlastní aktuálny používateľ
        $services = $serviceRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'services' => $services, // Poslanie správnych dát do šablóny
        ]);
    }
    #[Route('/edit', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
