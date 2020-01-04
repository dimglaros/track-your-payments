<?php

namespace App\Repository;

use App\Entity\PaymentType;
use App\Exception\NonValidPaymentTypeException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method PaymentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentType[]    findAll()
 * @method PaymentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentType::class);
    }

    /**
     * @throws NonValidPaymentTypeException
     */
    public function findByName(string $name) : PaymentType
    {
        try {
            return $this->createQueryBuilder('pt')
                ->andWhere('pt.name = :val')
                ->setParameter('val', $name)
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            throw new NonValidPaymentTypeException();
        } catch (NoResultException $e) {
            throw new NonValidPaymentTypeException();
        }
    }
}
