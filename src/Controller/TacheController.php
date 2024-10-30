<?php


namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;

class TacheController extends AbstractController
{
    #[Route('/tache', name: 'app_tache')]
    public function index(TacheRepository $tacheRepository): Response
    {
    
        $taches = $tacheRepository->findBy(['statut' => ['a_faire', 'en_cours']]);

        
        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/tache/new', name: 'app_tache_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tache);
            $entityManager->flush(); 

            return $this->redirectToRoute('app_tache'); 
        }

        return $this->render('tache/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tache/edit/{id}', name: 'app_tache_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, TacheRepository $tacheRepository, int $id): Response
    {
        $tache = $tacheRepository->find($id);

        if (!$tache) {
            throw $this->createNotFoundException('Tâche non trouvée');
        }

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); 

            return $this->redirectToRoute('app_tache');
        }

        return $this->render('tache/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tache/delete/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, TacheRepository $tacheRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $tache = $tacheRepository->find($id);

        if (!$tache) {
            throw $this->createNotFoundException('Tâche non trouvée');
        }

        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tache');
    }
}
