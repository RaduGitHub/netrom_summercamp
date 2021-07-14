<?php


namespace App\Repository;


use App\Entity\LicensePlate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicensePlate::class);
    }

//    /**
//     * @throws NonUniqueResultException
//     */
//    public function findByBlockee($value): ?string
//    {
//
//        $bl =  $this->createQueryBuilder('1')
//            ->andWhere('1.blockee = :val' )
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult();
//
//        if($bl == null)
//        {
//            return 'exist';
//        }
//        else
//        {
//            return 'dontex';
//        }
//    }
//
//    /**
//     * @throws NonUniqueResultException
//     */
//    public function findByBlocker($value): ?string
//    {
//
//        $bl = $this->createQueryBuilder('1')
//            ->andWhere('1.blocker = :val' )
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult();
//
//        if($bl == null)
//        {
//            return 'exist';
//        }
//        else
//        {
//            return 'dontex';
//        }
//    }

    public function findByBlocker($value): ?array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.blocker = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByBlockee($value): ?array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.blockee = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function addActivityBlocker(string $licensePlateBlockee, string $licensePlateBlocker){
        //$this->createQueryBuilder()
    }

}