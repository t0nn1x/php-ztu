<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService extends AbstractCrudService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Reservation::class);
    }

    /**
     * Find reservations by user
     */
    public function findByUser(User $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * Find reservations by date range
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->repository->createQueryBuilder('r')
            ->where('r.checkIn <= :endDate')
            ->andWhere('r.checkOut >= :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find reservations by status
     */
    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status]);
    }

    /**
     * Find reservations by room
     */
    public function findByRoom(Room $room): array
    {
        return $this->repository->createQueryBuilder('r')
            ->join('r.reservationRooms', 'rr')
            ->where('rr.room = :room')
            ->setParameter('room', $room)
            ->getQuery()
            ->getResult();
    }

    /**
     * Calculate total price for a reservation
     */
    public function calculateTotalPrice(Reservation $reservation): float
    {
        $total = 0;
        foreach ($reservation->getReservationRooms() as $reservationRoom) {
            $roomType = $reservationRoom->getRoom()->getRoomType();
            $nights = $reservation->getCheckInDate()->diff($reservation->getCheckOutDate())->days;
            $total += (float) $roomType->getPricePerNight() * $nights;
        }
        return $total;
    }
} 
