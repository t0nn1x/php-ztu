<?php

namespace App\Controller;

use App\Entity\Amenity;
use App\Service\AmenityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/amenities')]
class AmenityController extends AbstractApiController
{
    private AmenityService $amenityService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        AmenityService $amenityService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($amenityService, $serializer, $validator);
        $this->amenityService = $amenityService;
        $this->entityManager = $entityManager;
    }

    protected function getEntityClass(): string
    {
        return Amenity::class;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return ['amenity:read'];
    }

    #[Route('', name: 'api_amenities_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        return $this->getCollection($request);
    }

    #[Route('/{id}', name: 'api_amenities_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->getItem($id);
    }

    #[Route('', name: 'api_amenities_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createItem($request);
    }

    #[Route('/{id}', name: 'api_amenities_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->updateItem($request, $id);
    }

    #[Route('/{id}', name: 'api_amenities_patch', methods: ['PATCH'])]
    public function patch(Request $request, int $id): JsonResponse
    {
        return $this->patchItem($request, $id);
    }

    #[Route('/{id}', name: 'api_amenities_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteItem($id);
    }

    #[Route('/by-room-type/{roomTypeId}', name: 'api_amenities_by_room_type', methods: ['GET'])]
    public function byRoomType(int $roomTypeId): JsonResponse
    {
        $roomType = $this->entityManager->getReference('App\Entity\RoomType', $roomTypeId);
        $amenities = $this->amenityService->findByRoomType($roomType);

        return $this->json([
            'items' => $amenities
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-category/{category}', name: 'api_amenities_by_category', methods: ['GET'])]
    public function byCategory(string $category): JsonResponse
    {
        $amenities = $this->amenityService->findByCategory($category);

        return $this->json([
            'items' => $amenities
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/featured', name: 'api_amenities_featured', methods: ['GET'])]
    public function featured(): JsonResponse
    {
        $amenities = $this->amenityService->findFeatured();

        return $this->json([
            'items' => $amenities
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }
} 
