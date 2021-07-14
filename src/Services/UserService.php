<?php


namespace App\Services;

use App\Entity\Activity;
use App\Entity\LicensePlate;
use App\Entity\User;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class UserService
{

    protected UserRepository $userRepo;
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->userRepo = $em->getRepository(User::class);
    }

    public function getEmailByUid($uid): ?string
    {
        return $this->userRepo->getEmailByUid($uid);
    }



}