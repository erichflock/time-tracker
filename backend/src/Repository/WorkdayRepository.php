<?php

namespace App\Repository;

use App\Entity\Workday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workday>
 */
class WorkdayRepository extends ServiceEntityRepository implements WorkdayRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workday::class);
    }

    public function save(Workday $workday): void 
    {
        $em = $this->getEntityManager();
        $em->persist($workday);
        $em->flush();
    }

    public function findByDate(string $date): ?Workday
    {
        return $this->findOneBy(['date' => $date]);
    }

    public function findAll(): array 
    {
        return parent::findAll();
    }
}