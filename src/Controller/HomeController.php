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
        return $this->render('home/home.html.twig');
    }

    /**
     * @param Request $request
     * @param $QUERY
     *
     * @Route("handleSearch/{QUERY?}", name="handle_search", methods={"POST", "GET"})
     */
    public function handleSearchRequest(Request $request, $QUERY): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        echo 'Am ajuns aici';
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
