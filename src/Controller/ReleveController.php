<?php

namespace App\Controller;

use App\Entity\Releve;
use App\Form\ReleveType;
use App\Repository\ReleveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/releve')]
class ReleveController extends AbstractController
{
    #[Route('/', name: 'app_releve_index', methods: ['GET'])]
    public function index(ReleveRepository $releveRepository): Response
    {

        return $this->render('releve/index.html.twig', [
            'releve' => $releveRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_releve_voir', methods: ['GET'])]
    public function show(Releve $releve): Response
    {
        return $this->render('releve/voir.html.twig', [
            'releve' => $releve,
        ]);
    }

    #[Route('/ajouter', name: 'app_releve_ajouter', methods: ['GET', 'POST'])]
    public function ajouter(Request $request, ManagerRegistry $doctrine): Response
    {
        $releve = new Releve();

        $form = $this->createForm(ReleveType::class, $releve);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($releve);
            $em->flush();
            return $this->redirectToRoute('app_releve_index');
        }

        return $this->render('releve/ajouter.html.twig', [
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_releve_modifier', methods: ['GET', 'POST'])]
    public function edit(Request $request, Releve $releve, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReleveType::class, $releve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_releve_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('releve/modifier.html.twig', [
            'releve' => $releve,
            'formulaire' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_releve_supprimer', methods: ['POST'])]
    public function delete(Request $request, Releve $releve, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('supprimer'.$releve->getId(), $request->request->get('_token'))) {
            $entityManager->remove($releve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_releve_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/tableau/{id}', name: 'app_tableau')]
    public function generateTable(int $id, ReleveRepository $releveRepository): Response
    {
        $releve = $releveRepository->find($id);

        if (!$releve) {
            throw $this->createNotFoundException('Relevé non trouvé');
        }

        $releveBrut = $releve->getReleveBrut();

        $releveArray = explode('/', $releveBrut);

        $tableau = array_fill(0, 3, array_fill(0, 3, 0));

        foreach ($releveArray as $index => $value) {
            $row = (int)($index / 3);
            $col = $index % 3;
            $tableau[$row][$col] = (int)$value;
        }

        return $this->render('releve/tableau.html.twig', [
            'tableau' => $tableau,
        ]);
    }

    #[Route('/visualisation/{id}', name: 'app_visualisation')]
    public function generateVisualisation(int $id, ReleveRepository $releveRepository): Response
    {
        $releve = $releveRepository->find($id);

        if (!$releve) {
            throw $this->createNotFoundException('Relevé non trouvé');
        }

        $releveBrut = $releve->getReleveBrut();

        $visualisation = $this->generateRandomVisualisation($releveBrut);

        return $this->render('releve/visualisation.html.twig', [
            'visualisation' => $visualisation,
        ]);
    }


    private function generateRandomVisualisation(string $releveBrut): array
    {
        $visualisation = array_fill(0, 9, array_fill(0, 9, 0));

        $releveArray = explode('/', $releveBrut);

        foreach ($releveArray as $index => $value) {
            $row = (int)($index / 3) * 3;
            $col = ($index % 3) * 3;

            $greenCount = (int)$value;

            // On génère un tableau avec toutes les positions possibles
            $positions = array_fill(0, 9, 0);
            $availablePositions = range(0, 8);
            shuffle($availablePositions);

            // On place les cases vertes dans des positions aléatoires
            for ($i = 0; $i < $greenCount; $i++) {
                $position = array_shift($availablePositions);
                $rowOffset = (int)($position / 3);
                $colOffset = $position % 3;

                $visualisation[$row + $rowOffset][$col + $colOffset] = 1;
            }
        }
        return $visualisation;
    }
}
