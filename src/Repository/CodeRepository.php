<?php

namespace App\Repository;

use App\Entity\Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Code>
 */
class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    public function findValidCodes(?UserInterface $user): array
    {
        $now = $now ?? new \DateTimeImmutable();
        $delay = $user && \in_array('ROLE_VIP', $user->getRoles(), true)
            ? $now->modify('+2 day')
            : $now;

        $queryBuilder = $this->createQueryBuilder('c')
            ->where('c.validFrom <= :delay')
            ->andWhere('c.validUntil >= :now')
            ->setParameter('delay', $delay)
            ->setParameter('now', $now)
            ->orderBy('c.validUntil', 'ASC');

        if (!$user || !\in_array('ROLE_VIP', $user->getRoles(), true)) {
            $queryBuilder->andWhere('c.isVipOnly = :isVip')
                ->setParameter('isVip', false);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
