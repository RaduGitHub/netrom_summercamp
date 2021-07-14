<?php


namespace App\Controller;


use App\Entity\LicensePlate;
use App\Form\IGotBlockedActivityType;
use App\Form\UserType;
use App\Services\ActivityService;
use App\Services\LicensePlateService;
use App\Services\MailerService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Activity;
use App\Form\IBlockedActivityType;
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
        $form = $this->createForm(IBlockedActivityType::class, $activity);
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

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/iblocked', name: 'iblocked')]
    public function iBlocked(Request $request, ActivityService $activityService, LicensePlateService $licensePlateService):Response
    {
        $activity = new Activity();
        $lp_count = $licensePlateService->getLP($this->getUser()->getId());

        if($lp_count == 1){
            $form = $this->createForm(IBlockedActivityType::class, $activity, [
                'carCount' => 0
            ]);
        }elseif ($lp_count > 1){
            $form = $this->createForm(IBlockedActivityType::class, $activity, [
                'carCount' => 1
            ]);
        }else{
            $this->addFlash('notice', 'You must add a license plate first');
            return $this->redirectToRoute('license_plate_new');
        }

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
    public function iGotBlocked(Request $request, ActivityService $activityService, LicensePlateService $licensePlateService, MailerService $mailer, UserService $userService):Response
    {
        $activity = new Activity();

        $lp_count = $licensePlateService->getLP($this->getUser()->getId());
        if($lp_count == 1){
            $form = $this->createForm(IGotBlockedActivityType::class, $activity, [
                'carCount' => 1
            ]);
        }elseif ($lp_count > 1){
            $form = $this->createForm(IGotBlockedActivityType::class, $activity, [
                'carCount' => 2
            ]);
        }else{
            $this->addFlash('notice', 'You must add a license plate first');
            return $this->redirectToRoute('license_plate_new');
        }

        $form->handleRequest($request);

        //blockee = me , blocker = you
        if($form->isSubmitted() && $form->isValid())
        {
            //$licensePlate->setUserId($this->getUser()->getId());
            $lpBlockee = $licensePlateService->cleanLP($activity->getBlockee());
            $lpBlocker = $licensePlateService->cleanLP($activity->getBlocker());
            if($activityService->checkLicensePlate($lpBlockee, $this->getUser()->getId()) == 'create')
            {
                $licensePlate = new LicensePlate();
                $licensePlate->setLicensePlate($lpBlockee);
                $licensePlate->setUserId($this->getUser()->getId());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($licensePlate);
                $entityManager->flush();
            }
            if($activityService->checkLicensePlate($lpBlocker) == 'create')
            {
                $licensePlate = new LicensePlate();
                $licensePlate->setLicensePlate($lpBlocker);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($licensePlate);
                $entityManager->flush();
            }
            else
            {
                $activity->setStatus(1);

                //$mailer->sendEmailBlocker();
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


}