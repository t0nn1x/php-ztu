<?php

namespace App\Service;

use App\Entity\Amenity;
use App\Entity\Room;
use App\Entity\RoomAmenity;
use App\Repository\RoomAmenityRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoomAmenityService extends AbstractEntityService
{
    private RoomAmenityRepository $roomAmenityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        RoomAmenityRepository $roomAmenityRepository
    ) {
        parent::__construct($entityManager);
        $this->roomAmenityRepository = $roomAmenityRepository;
    }

    protected function getEntityClass(): string
    {
        return RoomAmenity::class;
    }

    /**
     * Find room amenities by room
     */
    public function findByRoom(Room $room): array
    {
        return $this->roomAmenityRepository->findBy(['room' => $room]);
    }

    /**
     * Find room amenities by amenity
     */
    public function findByAmenity(Amenity $amenity): array
    {
        return $this->roomAmenityRepository->findBy(['amenity' => $amenity]);
    }

    /**
     * Add amenity to room
     */
    public function addAmenityToRoom(Room $room, Amenity $amenity): RoomAmenity
    {
        $roomAmenity = new RoomAmenity();
        $roomAmenity->setRoom($room);
        $roomAmenity->setAmenity($amenity);
        
        $this->save($roomAmenity);
        
        return $roomAmenity;
    }

    /**
     * Remove amenity from room
     */
    public function removeAmenityFromRoom(Room $room, Amenity $amenity): void
    {
        $roomAmenity = $this->roomAmenityRepository->findOneBy([
            'room' => $room,
            'amenity' => $amenity
        ]);

        if ($roomAmenity) {
            $this->remove($roomAmenity);
        }
    }
} 
