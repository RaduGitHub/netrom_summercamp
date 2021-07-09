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

    public function checkLicensePlate(string $lp, int $uid = null): ?string
    {
        if($uid == null)
        {
            $licensePlate = $this->licensePlateRepo->LPExist($lp);
        }
        else
        {
            $licensePlate = $this->licensePlateRepo->checkLP($lp, $uid);
        }

        if($licensePlate != null)
        {
            return 'exist';
        }
        else
        {
            return 'create';
        }
    }

    /**
     * @throws NonUniqueResultException
     */
//    public function checkBlockerLP(string $lp)
//    {
//        $licensePlate = $this->licensePlateRepo->LPExist($lp);
//
//        if($exist instanceof LicensePlate)
//        {
//            //continue
//            return 'exists';
//        }
//        else
//        {
////            $this->licensePlateRepo->addLPNoUser($lp);
//            return 'create';
//        }
//    }

    // TO DO LATER ---- DROPDOWN RETRIEVE DATABASE
//    public function getLicensePlates(int $userId): ?array
//    {
//        $licensePlates = $this->licensePlateRepo->findByUserId($userId);
//
//    }
}