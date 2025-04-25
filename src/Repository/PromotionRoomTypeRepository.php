<?php

namespace App\Repository;

use App\Entity\PromotionRoomType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PromotionRoomType>
 *
 * @method PromotionRoomType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionRoomType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionRoomType[]    findAll()
 * @method PromotionRoomType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRoomTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionRoomType::class);
    }
} 
