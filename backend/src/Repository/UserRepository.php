<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
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

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function followUser(User $follower, User $following): void
    {
        $follower->addFollowing($following);
        $this->getEntityManager()->persist($follower);
        $this->getEntityManager()->flush();
    }

    public function unfollowUser(User $follower, User $following): void
    {
        $follower->removeFollowing($following);
        $this->getEntityManager()->persist($follower);
        $this->getEntityManager()->flush();
    }

    public function searchUsersByEmail(string $searchTerm, int $limit = 5)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->andWhere($qb->expr()->like('u.email', ':searchTerm'))
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEmail($email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneById($id): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
