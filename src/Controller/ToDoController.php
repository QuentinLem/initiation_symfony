<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class ToDoController extends AbstractController
{
    #[Route('/to/do', name: 'app_to_do')]
    public function index(Request $request): Response {
        $session = $request->getSession();
        if(!$session->has('todos')){
            $todos = [
                'achat'=>'acheter nouveau VTT',
                'compte'=>'faire les comptes du mois',
                'nourriture'=>'faire les courses'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste de todos vient d'être initialisée avec succés ! ");
        }
        return $this->render('to_do/index.html.twig', [
            'controller_name' => 'ToDoController',
        ]);
    }

    #[Route('to/do/add/{name}/{content}', name:'to_do.add')]
    public function addTodo(Request $request, $name, $content): RedirectResponse {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(isset($todos[$name])){
                $this->addFlash('error', "L'entrée $name de todos existe déjà.");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "La liste de todos vient d'être mise à jour avec succés ! ");
            }
        } else {
            $this->addFlash('error', "La liste de todos n'est pas encore initialisée.");
        }
        return $this->redirectToRoute('app_to_do');
    }

    #[Route('to/do/update/{name}/{content}', name:'to_do.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error', "L'entrée $name de todos n'existe pas et ne peut être mise à jour.");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "La liste de todos vient d'être mise à jour avec succés ! ");
            }
        } else {
            $this->addFlash('error', "La liste de todos n'est pas encore initialisée.");
        }
        return $this->redirectToRoute('app_to_do');
    }

    #[Route('to/do/delete/{name}', name:'to_do.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse {
        $session = $request->getSession();
        if($session->has('todos')){
            $todos = $session->get('todos');
            if(!isset($todos[$name])){
                $this->addFlash('error', "L'entrée $name de todos n'existe pas et ne peut donc être supprimée.");
            } else {
                unset($todos[$name]);
                $session->set('todos', $todos);
        
                $this->addFlash('success', "La liste de todos vient d'être mise à jour avec succés ! (suppression de $name) ");
            }
        } else {
            $this->addFlash('error', "La liste de todos n'est pas encore initialisée.");
        }
        return $this->redirectToRoute('app_to_do');
    }

    #[Route('to/do/reset', name:'to_do.reset')]
    public function resetTodo(Request $request): RedirectResponse {
        $session = $request->getSession();
        if($session->has('todos')){
            $session->remove('todos');
            $this->addFlash('success', "La liste de todos a été réinitialisée avec succés.");
        } else {
            $this->addFlash('info', 'Pas de liste todo à supprimer.');
        }
        return $this->redirectToRoute('app_to_do');
    }
}
