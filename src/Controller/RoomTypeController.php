<?php

namespace App\Controller;

use App\Entity\RoomType;
use App\Service\RoomTypeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/room-types')]
class RoomTypeController extends AbstractApiController
{
    private RoomTypeService $roomTypeService;

    public function __construct(
        RoomTypeService $roomTypeService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($roomTypeService, $serializer, $validator);
        $this->roomTypeService = $roomTypeService;
    }

    protected function getEntityClass(): string
    {
        return RoomType::class;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return ['room_type:read'];
    }

    #[Route('', name: 'api_room_types_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        return $this->getCollection($request);
    }

    #[Route('/{id}', name: 'api_room_types_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->getItem($id);
    }

    #[Route('', name: 'api_room_types_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createItem($request);
    }

    #[Route('/{id}', name: 'api_room_types_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->updateItem($request, $id);
    }

    #[Route('/{id}', name: 'api_room_types_patch', methods: ['PATCH'])]
    public function patch(Request $request, int $id): JsonResponse
    {
        return $this->patchItem($request, $id);
    }

    #[Route('/{id}', name: 'api_room_types_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteItem($id);
    }

    #[Route('/with-promotions', name: 'api_room_types_with_promotions', methods: ['GET'])]
    public function withPromotions(): JsonResponse
    {
        $roomTypes = $this->roomTypeService->findWithActivePromotions();

        return $this->json([
            'items' => $roomTypes
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-capacity/{capacity}', name: 'api_room_types_by_capacity', methods: ['GET'])]
    public function byCapacity(int $capacity): JsonResponse
    {
        $roomTypes = $this->roomTypeService->findByCapacity($capacity);

        return $this->json([
            'items' => $roomTypes
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }
} 
