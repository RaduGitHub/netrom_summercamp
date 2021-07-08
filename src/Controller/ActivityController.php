<?php


namespace App\Controller;


use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Activity;
use App\Form\ActivityType;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity')]
class ActivityController extends AbstractController
{

    #[Route('/', name: 'activity')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);

        return $this->render('home/home.html.twig');

    }

    #[Route('/new', name: 'activity_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //to do
        }
        //to do
        //return $this->render ('')



//        $user = new User();
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $user->setPassword($passHaser->hashPassword(
//                $user,
//                $user->getPassword()
//            ));
//            //$user->setRole();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('home');
//        }
//
//        return $this->render('user/register.html.twig', [
//            'user' => $user,
//            'form' => $form->createView(),
//        ]);
    }
}