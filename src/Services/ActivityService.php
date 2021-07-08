<?php


namespace App\Services;

use App\Entity\Activity;
use App\Entity\LicensePlate;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class ActivityService
{
    /**
     * @var ActivityRepository
     */
    protected $activityRepo;
    protected $licensePlateRepo;
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->activityRepo = $em->getRepository(Activity::class);
        $this->licensePlateRepo = $em->getRepository(LicensePlate::class);
    }

    public function addBlock(string $licensePlateBlockee, string $licensePlateBlocker)
    {
        $status = $this->activityRepo->addActivityBlocker($licensePlateBlockee, $licensePlateBlocker);

        if($status == 'Added'){
            //do something
        }else{
            //throw error
        }
    }

    public function iveBlockedSomebody(string $licensePlate)
    {
        $blocker = $this->activityRepo->findByBlocker($licensePlate);

        if ($blocker instanceof Activity){
            return $blocker->getBlockee();
        }
        return '';
    }

    /**
     * @param string $licensePlate
     * @return string|null
     * @throws NonUniqueResultException
     */
    public function whoBlockedMe(string $licensePlate): ?string
    {
        $blocker = $this->activityRepo->findByBlockee($licensePlate);

        if ($blocker instanceof Activity){
            return $blocker->getBlocker();
        }
        return '';
    }

    /**
     * @throws NonUniqueResultException
     */
    public function checkLicensePlate(string $lp, int $uid)
    {
        $lp = $this->licensePlateRepo->checkLicensePlate($lp, $uid);

        if($lp instanceof LicensePlate)
        {
            //return 'exists';
        }
        else
        {
            $this->licensePlateRepo->addLicensePlate($lp, $uid);
            //return 'created';
        }
    }

    // TO DO LATER ---- DROPDOWN RETRIEVE DATABASE
//    public function getLicensePlates(int $userId): ?array
//    {
//        $licensePlates = $this->licensePlateRepo->findByUserId($userId);
//
//    }
}