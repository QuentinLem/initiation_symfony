<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TabController extends AbstractController
{
    #[Route('/tab/{nb<\d+>?5}', name: 'app_tab')]
    public function index($nb): Response
    {
        $notes = [];
        for($i=0;$i<$nb;$i++){
            $notes[]=rand(0,20);
        }
        return $this->render('tab/index.html.twig', [
            'controller_name' => 'TabController',
            'notes' => $notes,
        ]);
    }

    #[Route('/tab/users', name: 'app_tab.users')]
    public function getUsers(): Response
    {
        $users = [
            ['firstname' => 'Jean', 'name' => 'Dupont', 'age' => 18],
            ['firstname' => 'Youssef', 'name' => 'Dupont', 'age' => 24],
            ['firstname' => 'Jean', 'name' => 'Bébère', 'age' => 62],
        ];
    
        return $this->render('tab/users.html.twig', [
            'controller_name' => 'TabController',
            'users' => $users,
        ]);
    }
}
