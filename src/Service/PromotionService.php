<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Entity\RoomType;
use Doctrine\ORM\EntityManagerInterface;

class PromotionService extends AbstractCrudService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Promotion::class);
    }

    /**
     * Find active promotions
     */
    public function findActivePromotions(): array
    {
        $now = new \DateTime();
        
        return $this->repository->createQueryBuilder('p')
            ->where('p.startDate <= :now')
            ->andWhere('p.endDate >= :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find promotions by room type
     */
    public function findByRoomType(RoomType $roomType): array
    {
        return $this->repository->createQueryBuilder('p')
            ->join('p.promotionRoomTypes', 'prt')
            ->where('prt.roomType = :roomType')
            ->setParameter('roomType', $roomType)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find promotions by date range
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->repository->createQueryBuilder('p')
            ->where('p.startDate <= :endDate')
            ->andWhere('p.endDate >= :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Calculate discounted price
     */
    public function calculateDiscountedPrice(float $originalPrice, Promotion $promotion): float
    {
        if ($promotion->getDiscountType() === 'percentage') {
            return $originalPrice * (1 - $promotion->getDiscountValue() / 100);
        }
        
        return max(0, $originalPrice - $promotion->getDiscountValue());
    }
} 
