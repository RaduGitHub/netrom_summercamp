<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\LicensePlate;
use App\Form\IBlockedActivityType;
use App\Form\IGotBlockedActivityType;
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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/iblocked', name: 'iblocked')]
    public function iBlocked(Request $request, ActivityService $activityService):Response
    {
        $activity = new Activity();
        $form = $this->createForm(IBlockedActivityType::class, $activity);
        $form->handleRequest($request);
        //blockee = you , blocker = me
        if($form->isSubmitted() && $form->isValid())
        {
            //$licensePlate->setUserId($this->getUser()->getId());

            if($activityService->checkLicensePlate($activity->getBlockee()) == 'create')
            {
                $licensePlate = new LicensePlate();
                $licensePlate->setLicensePlate($activity->getBlockee());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($licensePlate);
                $entityManager->flush();
            }
            else
            {
                $activity->setStatus(1);
            }
            if($activityService->checkLicensePlate($activity->getBlocker(), $this->getUser()->getId()) == 'create')
            {
                $licensePlate = new LicensePlate();
                $licensePlate->setLicensePlate($activity->getBlocker());
                $licensePlate->setUserId($this->getUser()->getId());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($licensePlate);
                $entityManager->flush();
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('home/iblocked.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/iGotBlocked', name: 'iGotBlocked')]
    public function iGotBlocked(Request $request, ActivityService $activityService):Response
    {
        $activity = new Activity();
        $form = $this->createForm(IGotBlockedActivityType::class, $activity);
        $form->handleRequest($request);
        //blockee = me , blocker = you
        if($form->isSubmitted() && $form->isValid())
        {
            //$licensePlate->setUserId($this->getUser()->getId());

            if($activityService->checkLicensePlate($activity->getBlockee(), $this->getUser()->getId()) == 'create')
            {
                $licensePlate = new LicensePlate();
                $licensePlate->setLicensePlate($activity->getBlockee());
                $licensePlate->setUserId($this->getUser()->getId());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($licensePlate);
                $entityManager->flush();
            }
            if($activityService->checkLicensePlate($activity->getBlocker()) == 'create')
            {
                $licensePlate = new LicensePlate();
                $licensePlate->setLicensePlate($activity->getBlocker());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($licensePlate);
                $entityManager->flush();
            }
            else
            {
                $activity->setStatus(1);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('home/iGotBlocked.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
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
