<?php

namespace App\Repository;

use App\Entity\LicensePlate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LicensePlate|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicensePlate|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicensePlate[]    findAll()
 * @method LicensePlate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicensePlateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicensePlate::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function checkLicensePlate(string $lp, int $uid)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.license_plate = :val1 AND l.user_id = :val2')
            ->setParameters(array([
                new Parameter('val1', $lp),
                new Parameter('val2', $uid),
            ]))
            ->getQuery()
            ->getArrayResult();
    }

    // TO DO ----- Create query to insert
    public function addLicensePlate(string $lp, int $uid)
    {
        $licensePlate = new LicensePlate();
        $licensePlate->setLicensePlate($lp);
        $licensePlate->setUserId($uid);
        $entityManager = $this->getDoctrine()->getManager();
//        $licensePlate->setUserId($this->getUser()->getId());
        $entityManager->persist($licensePlate);
        $entityManager->flush();
    }

    //TO DO LATER ---- DROPDOWN RETRIEVE FROM DATABASE
//    public function findByUserId(int $userId): ?array
//    {
////        return $this->createQueryBuilder('l')
//        dd($this->createQueryBuilder()
//            ->select('l.license_plate')
//            ->from('LicensePlate, l')
//            ->Where('l.userId = :val')
//            ->setParameter('val', $userId)
//            ->
//    }


    // /**
    //  * @return LicensePlate[] Returns an array of LicensePlate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LicensePlate
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
