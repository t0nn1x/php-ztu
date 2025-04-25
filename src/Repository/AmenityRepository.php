<?php

namespace App\Repository;

use App\Entity\Amenity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amenity>
 *
 * @method Amenity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Amenity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Amenity[]    findAll()
 * @method Amenity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmenityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amenity::class);
    }
} 
