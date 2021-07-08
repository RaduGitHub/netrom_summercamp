<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Services\ActivityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/iblocked', name: 'iblocked')]
    public function iBlocked(Request $request, ActivityService $activityService):Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //$licensePlate->setUserId($this->getUser()->getId());

            $activityService->checkLicensePlate($activity->getBlockee(), $this->getUser()->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('/');
        }

        return $this->render('home/iblocked.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
        //to do,
        //return $this->render ('')

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


//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/HomeController.php',
//        ]);
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
