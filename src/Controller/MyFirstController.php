<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyFirstController extends AbstractController
{
    #[Route('/my/first', name: 'app_my_first')]
    public function index(): Response
    {
        return $this->render('my_first/index.html.twig', [
            'controller_name' => 'MyFirstController',
            'path' => '',
            'nom' => 'Quentin',
            'age' => '18',
        ]);
    }
}
