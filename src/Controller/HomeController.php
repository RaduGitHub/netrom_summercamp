<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/HomeController.php',
//        ]);

        return $this->render('home/home.html.twig');

    }

//    /**
//     * @Route("/home/{scrub}, name="customPage")
//     */
//    public function customPageAction(string $scrub): Response
//    {
//        return $this->json([
//            'pageName' => $scrub,
//            'didItWork' => 'yes',
//        ]);
//    }
}
