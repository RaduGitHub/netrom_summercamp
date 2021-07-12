<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\LicensePlate;
use App\Form\IBlockedActivityType;
use App\Form\IGotBlockedActivityType;
use App\Services\ActivityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * @param Request $request
     * @param $_query
     *
     * @Route("handleSearch/{QUERY?}", name="handle_search", methods={"POST", "GET"})
     */
    public function handleSearchRequest(Request $request, $QUERY)
    {
        $em = $this->getDoctrine()->getManager();

        if($QUERY)
        {
            $data = $em->getRepository(LicensePlate::class)->findByLP($QUERY);
        }
        else
        {
            $data = $em->getRepository(LicensePlate::class)->findAllForUser($this->getUser()->getId());
        }

        $normalizers = [
            new ObjectNormalizer()
        ];

        $encoders = [
            new JsonEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($data, 'json');

        return new JsonResponse($data, 200, [], true);

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
