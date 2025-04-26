<?php

namespace App\Service;

use App\Entity\Amenity;
use App\Entity\RoomType;
use App\Repository\AmenityRepository;
use Doctrine\ORM\EntityManagerInterface;

class AmenityService extends AbstractEntityService
{
    private AmenityRepository $amenityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        AmenityRepository $amenityRepository
    ) {
        parent::__construct($entityManager);
        $this->amenityRepository = $amenityRepository;
    }

    protected function getEntityClass(): string
    {
        return Amenity::class;
    }

    /**
     * Find amenities by room type
     */
    public function findByRoomType(RoomType $roomType): array
    {
        return $this->amenityRepository->findByRoomType($roomType);
    }

    /**
     * Find amenities by category
     */
    public function findByCategory(string $category): array
    {
        return $this->amenityRepository->findBy(['category' => $category]);
    }

    /**
     * Find featured amenities
     */
    public function findFeatured(): array
    {
        return $this->amenityRepository->findBy(['isFeatured' => true]);
    }
} 
