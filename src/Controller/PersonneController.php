<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'app_personne.list')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }

    #[Route('/add', name: 'app_personne.add')]
    public function addPersonne(PersistenceManagerRegistry $doctrine): Response
    {
        //$this->getDoctrine //version symfony <= 5
        $entityManager = $doctrine->getManager();
        
        $personne = new Personne();
        $personne->setFirstname('Quentin');
        $personne->setName('Lem');
        $personne->setAge(28);

        // Ajoute l'opération d'insertion de la personne dans ma transaction
        $entityManager->persist($personne);
        // execute la transaction
        $entityManager->flush();

        return $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
    }
}
