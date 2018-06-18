<?php

namespace App\Repository;

use App\Entity\RequestOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RequestOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestOffer[]    findAll()
 * @method RequestOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RequestOffer::class);
    }

//    /**
//     * @return RequestOffer[] Returns an array of RequestOffer objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestOffer
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
