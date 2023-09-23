<?php

namespace Freelance\Repository;

use DateTimeInterface;
use Doctrine\Persistence\ManagerRegistry;
use Freelance\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @return Job[]
     */
    public function findByCreatedAtRange(DateTimeInterface $start, DateTimeInterface $end)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.createdAt >= :start')
            ->andWhere('j.createdAt < :end')
            ->setParameter('start', $start->format('c'))
            ->setParameter('end', $end->format('c'))
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $id
     * @return iterable|Job[]
     */
    public function findAfterId(int $id): iterable
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id > :id')
            ->setParameter('id', $id)
            ->orderBy('j.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function hasJobByUrl(string $url): bool
    {
        return (bool) $this->findBy(['url' => $url], null, 1);
    }

    public function save(Job $job): void
    {
        $this->_em->persist($job);
        $this->_em->flush();
    }
}
