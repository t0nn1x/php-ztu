<?php

namespace App\Service;

use App\Entity\Review;
use App\Entity\Room;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReviewService extends AbstractEntityService
{
    private ReviewRepository $reviewRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReviewRepository $reviewRepository
    ) {
        parent::__construct($entityManager);
        $this->reviewRepository = $reviewRepository;
    }

    protected function getEntityClass(): string
    {
        return Review::class;
    }

    /**
     * Find reviews by room
     */
    public function findByRoom(Room $room): array
    {
        return $this->reviewRepository->findBy(['room' => $room], ['createdAt' => 'DESC']);
    }

    /**
     * Find reviews by rating
     */
    public function findByRating(int $rating): array
    {
        return $this->reviewRepository->findBy(['rating' => $rating], ['createdAt' => 'DESC']);
    }

    /**
     * Get average rating for a room
     */
    public function getAverageRatingForRoom(Room $room): float
    {
        return $this->reviewRepository->getAverageRatingForRoom($room);
    }
} 
