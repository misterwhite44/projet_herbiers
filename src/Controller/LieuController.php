<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieu')]
class LieuController extends AbstractController
{
    #[Route('/', name: 'app_lieu_index', methods: ['GET'])]
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('lieu/index.html.twig', [
            'lieu' => $lieuRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_voir', methods: ['GET'])]
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/voir.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/ajouter', name: 'app_lieu_ajouter', methods: ['GET', 'POST'])]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lieu);
            $entityManager->flush();

            return $this->redirectToRoute('app_lieu_index');
        }

        return $this->render('lieu/ajouter.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_lieu_modifier', methods: ['GET', 'POST'])]
    public function modifier(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lieu/modifier.html.twig', [
            'lieu' => $lieu,
            'formulaire' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_lieu_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        try {
            if ($this->isCsrfTokenValid('supprimer' . $lieu->getId(), $request->request->get('_token'))) {
                $entityManager->remove($lieu);
                $entityManager->flush();

                $this->addFlash('success', 'Lieu supprimé avec succès.');
            }
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Impossible de supprimer ce lieu car il est lié à un ou plusieurs relevés.');
        }

        return $this->redirectToRoute('app_lieu_index');
    }
}
