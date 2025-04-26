<?php

namespace App\Controller;

use App\Entity\Room;
use App\Service\RoomService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/rooms')]
class RoomController extends AbstractApiController
{
    private RoomService $roomService;

    public function __construct(
        RoomService $roomService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($roomService, $serializer, $validator);
        $this->roomService = $roomService;
    }

    protected function getEntityClass(): string
    {
        return Room::class;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return ['room:read'];
    }

    #[Route('', name: 'api_rooms_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        return $this->getCollection($request);
    }

    #[Route('/{id}', name: 'api_rooms_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->getItem($id);
    }

    #[Route('', name: 'api_rooms_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createItem($request);
    }

    #[Route('/{id}', name: 'api_rooms_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->updateItem($request, $id);
    }

    #[Route('/{id}', name: 'api_rooms_patch', methods: ['PATCH'])]
    public function patch(Request $request, int $id): JsonResponse
    {
        return $this->patchItem($request, $id);
    }

    #[Route('/{id}', name: 'api_rooms_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteItem($id);
    }

    #[Route('/available', name: 'api_rooms_available', methods: ['GET'])]
    public function available(Request $request): JsonResponse
    {
        $checkIn = new \DateTime($request->query->get('checkIn'));
        $checkOut = new \DateTime($request->query->get('checkOut'));

        $rooms = $this->roomService->findAvailableRooms($checkIn, $checkOut);

        return $this->json([
            'items' => $rooms
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-type/{typeId}', name: 'api_rooms_by_type', methods: ['GET'])]
    public function byType(int $typeId): JsonResponse
    {
        $rooms = $this->roomService->findByType($typeId);

        return $this->json([
            'items' => $rooms
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }
} 
