<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    #[Route('/{id<\d+>}', name: 'app_personne.detail')]
    public function detail(PersistenceManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);

        if(!$personne){
            $this->addFlash('error', "La personne d'id $id n'existe pas.");
            return $this->redirectToRoute('app_personne.list');
        }

        return $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
    }

    // meme chose que detail() mais avec conversion automatique des parametres (Param Convertor)
    // route accessible via lien 2 sur card personne
    #[Route('/detail/{id<\d+>}', name: 'app_personne.detail2')]
    public function detail2(Personne $personne = null): Response
    {
        if(!$personne){
            $this->addFlash('error', "La personne n'existe pas.");
            return $this->redirectToRoute('app_personne.list');
        }

        return $this->render('personne/detail.html.twig', [
            'personne' => $personne,
        ]);
    }

    #[Route('/all/age/{ageMin<\d+>}/{ageMax<\d+>}', name: 'app_personne.all.age')]
    public function getPersonnesByAgeInterval(PersistenceManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findPersonnesByAgeInterval($ageMin, $ageMax);
        

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }

    // pagination à l'aide de findBy()
    #[Route('/all/{page<\d+>?1}/{nbElem<\d+>?12}', name: 'app_personne.all')]
    public function getAll(PersistenceManagerRegistry $doctrine, $page, $nbElem): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        $nbPage = ceil($nbPersonne/$nbElem);
        $personnes = $repository->findBy([], [], $nbElem, ($page-1)*$nbElem);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbPage' => $nbPage,
            'currentPage' => $page,
            'nbElem' => $nbElem,
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

    // utilisation du Param Convertor
    // se référer route:'app_personne.detail' pour une procédure sans Param Conv
    #[Route('/delete/{id<\d+>}', name:'app_personne.delete')]
    public function delete(Personne $personne = null, PersistenceManagerRegistry $doctrine): RedirectResponse {
        if($personne){
            $id_perso = strval($personne->getId());
            $manager = $doctrine->getManager();
            // ajoute l'action de suppression à la transaction
            $manager->remove($personne);
            // execute la transaction
            $manager->flush();
            $this->addFlash('success', "La personne (id:".$id_perso.") a été supprimée avec succés.");
        } else {
            $this->addFlash('error','La personne que vous essayez de supprimer n\'existe pas.' );
        }
        return $this->redirectToRoute('app_personne.all');
    }

    #[Route('/update/{id<\d+>}/{firstname?X}/{name?D}/{age?0}', name:'app_personne.update')]
    public function update(Personne $personne = null, PersistenceManagerRegistry $doctrine, $firstname, $name, $age): RedirectResponse {
        if($personne){
            $personne->setFirstname($firstname);
            $personne->setName($name);
            $personne->setAge($age);

            $manager = $doctrine->getManager();
            $manager->persist($personne);
            $manager->flush();

            $this->addFlash(
               'success',
               'Personne modifiée avec succés.'
            );
        } else {
            $this->addFlash(
               'error',
               'Personne inexistante.'
            );
        }
        return $this->redirectToRoute('app_personne.all');
    }
}
