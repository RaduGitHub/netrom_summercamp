<?php

namespace App\Controller;

use App\Entity\LicensePlate;
use App\Entity\User;
use App\Form\LicensePlateType;
use App\Repository\LicensePlateRepository;
use App\Services\ActivityService;
use App\Services\LicensePlateService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/license/plate')]
class LicensePlateController extends AbstractController
{

    #[Route('/', name: 'license_plate_index', methods: ['GET'])]
    public function index(LicensePlateRepository $licensePlateRepository): Response
    {
        return $this->render('license_plate/index.html.twig', [
            'license_plates' => $licensePlateRepository->findAllForUser($this->getUser()->getId()),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/new', name: 'license_plate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Security $security, ActivityService $activityService, LicensePlateRepository $licensePlateRepository, LicensePlateService $licensePlateService): ?Response
    {
        $licensePlate = new LicensePlate();
        $form = $this->createForm(LicensePlateType::class, $licensePlate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $licensePlate->setLicensePlate($licensePlateService->cleanLP($licensePlate->getLicensePlate()));
            $entityManager = $this->getDoctrine()->getManager();

            //$licensePlateRepository->findOneBy(['license_plate'=>$licensePlate->getLicensePlate()]);


            //should be ok annie
            $blockers = $activityService->whoBlockedMe($licensePlate->getLicensePlate());
            //dd($blockers);
            if($blockers != null){
                foreach($blockers as $blockerAc){
                    $blocker = $licensePlateRepository->findOneBy(['license_plate' => $blockerAc->getBlocker()]);
                    $this->addFlash('warning', 'Your car has been blocked by'.$blocker->getLicensePlate());

                    $blockerAc->setStatus(1);
                    $entityManager->persist($blockerAc);
                    $entityManager->flush();
                }
            }

            $blockees = $activityService->iveBlockedSomebody($licensePlate->getLicensePlate());
            if($blockees != null){
                foreach($blockees as $blockeeAc){
                    $blockee = $licensePlateRepository->findOneBy(['license_plate'=>$blockeeAc->getBlockee()]);
                    $this->addFlash('warning', 'Your car is blocking'.$blockee->getLicensePlate());

                    $blockerAc->setStatus(1);
                    $entityManager->persist($blockeeAc);
                    $entityManager->flush();
                }
            }

            $licensePlate->setUserId($this->getUser()->getId());
            $entityManager->persist($licensePlate);
            $entityManager->flush();

            $this->addFlash("danger", "Plate added");

            return $this->redirectToRoute('license_plate_index');

        }

        return $this->render('license_plate/new.html.twig', [
            'license_plate' => $licensePlate,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'license_plate_show', methods: ['GET'])]
    public function show(LicensePlate $licensePlate): Response
    {
        return $this->render('license_plate/show.html.twig', [
            'license_plate' => $licensePlate,
        ]);
    }

    #[Route('/{id}/edit', name: 'license_plate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LicensePlate $licensePlate, LicensePlateService $licensePlateService, ActivityService $activityService): Response
    {
        $form = $this->createForm(LicensePlateType::class, $licensePlate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newLicensePlate = $licensePlateService->cleanLP($licensePlate->getLicensePlate());

            // ??? case ( change everywhere [activity] and send message

            //reverse mby
            $blockers = $activityService->whoBlockedMe($newLicensePlate);
            $blockees = $activityService->iveBlockedSomebody($newLicensePlate);

            if($blockees != null || $blockers != null){
                $this->addFlash('danger', 'License plate can\'t be changed. There is an active report');
                return $this->redirectToRoute('license_plate_index');
            }

            $licensePlate->setLicensePlate($newLicensePlate);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'License plate changed successfully');
            return $this->redirectToRoute('license_plate_index');
        }

        return $this->render('license_plate/edit.html.twig', [
            'license_plate' => $licensePlate,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'license_plate_delete', methods: ['POST'])]
    public function delete(Request $request, LicensePlate $licensePlate, LicensePlateService $licensePlateService, ActivityService $activityService): Response
    {
        $lp = $licensePlateService->cleanLP($licensePlate->getLicensePlate());
        $blockers = $activityService->whoBlockedMe($lp);
        $blockees = $activityService->iveBlockedSomebody($lp);

        if($blockees != null || $blockers != null){
            $this->addFlash('danger', 'License plate can\'t be deleted. There is an active report');
            return $this->redirectToRoute('license_plate_index');
        }

        if ($this->isCsrfTokenValid('delete'.$licensePlate->getId(), $request->request->get('_token'))) {
            $this>$this->addFlash('success', 'LicensePlate'. $licensePlate->getLicensePlate() .'deleted');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($licensePlate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('license_plate_index');
    }
}
