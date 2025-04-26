<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\ReservationRoom;
use App\Entity\Room;
use App\Repository\ReservationRoomRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationRoomService extends AbstractEntityService
{
    private ReservationRoomRepository $reservationRoomRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationRoomRepository $reservationRoomRepository
    ) {
        parent::__construct($entityManager);
        $this->reservationRoomRepository = $reservationRoomRepository;
    }

    protected function getEntityClass(): string
    {
        return ReservationRoom::class;
    }

    /**
     * Find reservation rooms by reservation
     */
    public function findByReservation(Reservation $reservation): array
    {
        return $this->reservationRoomRepository->findBy(['reservation' => $reservation]);
    }

    /**
     * Find reservation rooms by room
     */
    public function findByRoom(Room $room): array
    {
        return $this->reservationRoomRepository->findBy(['room' => $room]);
    }

    /**
     * Check if room is available for the given dates
     */
    public function isRoomAvailable(Room $room, \DateTimeInterface $checkIn, \DateTimeInterface $checkOut): bool
    {
        return $this->reservationRoomRepository->isRoomAvailable($room, $checkIn, $checkOut);
    }
} 
