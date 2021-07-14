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

    /**
     * @param string $licensePlate
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function iveBlockedSomebody(string $licensePlate): ?array
    {
        $blocker = $this->activityRepo->findByBlocker($licensePlate);

        if (count($blocker) != 0){
            return $blocker;
        }
        return null;
    }

    /**
     * @param string $licensePlate
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function whoBlockedMe(string $licensePlate): ?array
    {
        $blocker = $this->activityRepo->findByBlockee($licensePlate);

        if (count($blocker) != 0){
            return $blocker;
        }
        return null;
    }

    /**
     * @throws NonUniqueResultException
     */
//    public function checkBlockeeLP(string $lp, int $uid): ?string
//    {
//        $licensePlate = $this->licensePlateRepo->checkLP($lp, $uid);
//
//        if($licensePlate != null)
//        {
//            return 'exists';
//        }
//        else
//        {
////            $this->licensePlateRepo->addLPUser($lp, $uid);
//            return 'create';
//        }
//    }

    public function checkBlockBlockee(string $lp): ?string
    {
        $blockee = $this->activityRepo->findByBlockee($lp);
        $blocker = $this->activityRepo->findByBlocker($lp);

        if($blockee == null and $blocker == null)
        {
            //nothing
            return null;
        }
        elseif ($blockee == 'exist')
        {
            //send mail
            return 'blockee';
        }
        elseif ($blocker == 'exist')
        {
            //send mail
            return 'blocker';
        }
    }

    public function checkLicensePlate(string $lp, int $uid = null): ?string
    {
        if ($uid == null) {
            $licensePlate = $this->licensePlateRepo->LPExist($lp);
        } else {
            $licensePlate = $this->licensePlateRepo->checkLP($lp, $uid);
        }

        if ($licensePlate != null) {
            return 'exist';
        } else {
            return 'create';
        }
    }
}