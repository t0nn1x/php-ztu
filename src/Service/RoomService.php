<?php

namespace App\Service;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;

class RoomService extends AbstractCrudService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Room::class);
    }

    /**
     * Find available rooms for given dates
     */
    public function findAvailableRooms(\DateTimeInterface $checkIn, \DateTimeInterface $checkOut): array
    {
        return $this->repository->createQueryBuilder('r')
            ->leftJoin('r.reservationRooms', 'rr')
            ->leftJoin('rr.reservation', 'res')
            ->where('res.id IS NULL OR NOT (
                :checkIn < res.checkOut AND :checkOut > res.checkIn
            )')
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find rooms by type
     */
    public function findByType(int $roomTypeId): array
    {
        return $this->findBy(['roomType' => $roomTypeId]);
    }
} 
