<?php

namespace App\Service;

use App\Entity\RoomType;
use Doctrine\ORM\EntityManagerInterface;

class RoomTypeService extends AbstractCrudService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, RoomType::class);
    }

    /**
     * Find room types with active promotions
     */
    public function findWithActivePromotions(): array
    {
        $now = new \DateTime();
        
        return $this->repository->createQueryBuilder('rt')
            ->leftJoin('rt.promotionRoomTypes', 'prt')
            ->leftJoin('prt.promotion', 'p')
            ->where('p.startDate <= :now')
            ->andWhere('p.endDate >= :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find room types by capacity
     */
    public function findByCapacity(int $capacity): array
    {
        return $this->findBy(['capacity' => $capacity]);
    }
} 
