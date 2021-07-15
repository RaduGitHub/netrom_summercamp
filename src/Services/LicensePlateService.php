<?php


namespace App\Services;


use App\Entity\Activity;
use App\Entity\LicensePlate;
use Doctrine\ORM\EntityManagerInterface;

class LicensePlateService
{

    protected $licensePlateRepo;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->licensePlateRepo = $em->getRepository(LicensePlate::class);
    }

    public function getLPcount($uid): ?int
    {
        return count($this->licensePlateRepo->findAllForUser($uid));
    }

    public function getLP($uid)
    {
        return $this->licensePlateRepo->findLp($uid);
    }

    public function cleanLP($lp)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $lp));
    }

    public function getUid($lp): array
    {
        return $this->licensePlateRepo->getUidForLP($lp);
    }
}