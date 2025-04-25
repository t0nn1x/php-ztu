<?php

namespace App\Repository;

use App\Entity\RoomAmenity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomAmenity>
 *
 * @method RoomAmenity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomAmenity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomAmenity[]    findAll()
 * @method RoomAmenity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomAmenityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomAmenity::class);
    }
} 
