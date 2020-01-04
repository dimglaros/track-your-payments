<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\NonValidUserException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonValidUserException
     */
    public function findByApiToken(string $apiToken) : User
    {
        if(empty($apiToken)) {
            throw new NonValidUserException();
        }

        try {
            return $this->createQueryBuilder('u')
                ->andWhere('u.apiToken = :val')
                ->setParameter('val', $apiToken)
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $e) {
            throw new NonValidUserException();
        } catch (NoResultException $e) {
            throw new NonValidUserException();
        }
    }
}
