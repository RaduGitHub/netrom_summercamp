<?php

namespace App\Repository;

use App\Entity\LicensePlate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Query\QueryBuilder;

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

    public function findAllForUser(int $uid): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user_id = :var')
            ->setParameter('var', $uid)
            ->getQuery()
            ->getArrayResult();
    }

    public function findLp(int $uid): ?string
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user_id = :var')
            ->setParameter('var', $uid)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByLP($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.license_plate like :query')
            ->setParameter('query', "%". $value ."%")
            ->getQuery()
            ->getResult();
    }

    public function getUidForLP($value): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.user_id')
            ->andWhere('l.license_plate = :var')
            ->setParameter('var', $value)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function checkLP(string $lp, int $uid)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.license_plate = :val1 AND l.user_id = :val2')
            ->setParameters(new ArrayCollection([
                new Parameter('val1', $lp),
                new Parameter('val2', $uid),
            ]))
            ->getQuery()
            ->getArrayResult();
    }

//    public function updateLP()

    // TO DO ----- Create query to insert
    public function addLPUser(string $lp, int $uid)
    {
//        $licensePlate = new LicensePlate();
//        $licensePlate->setLicensePlate($lp);
//        $licensePlate->setUserId($uid);
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($licensePlate);
//        $entityManager->flush();

        $this->createQueryBuilder('l')
            ->insert('license_plate')
            ->values(
                array(
                    'license_plate' => '?',
                    'user_id' => '?'
                )
            )
            ->setParameter(0, $lp)
            ->setParameter(1, $uid);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function LPExist(string $lp)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.license_plate = :val1')
            ->setParameter('val1', $lp)
            ->getQuery()
            ->getArrayResult();
    }

    // TO DO ------- Create query to insert
    public function addLPNoUser(string $lp)
    {
        $licensePlate = new LicensePlate();
        $licensePlate->setLicensePlate($lp);
//        $licensePlate->setUserId(null);
        $entityManager = $this->getDoctrine()->getManager();
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
