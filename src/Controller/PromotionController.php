<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Service\PromotionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/promotions')]
class PromotionController extends AbstractApiController
{
    private PromotionService $promotionService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PromotionService $promotionService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($promotionService, $serializer, $validator);
        $this->promotionService = $promotionService;
        $this->entityManager = $entityManager;
    }

    protected function getEntityClass(): string
    {
        return Promotion::class;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return ['promotion:read'];
    }

    #[Route('', name: 'api_promotions_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        return $this->getCollection($request);
    }

    #[Route('/{id}', name: 'api_promotions_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->getItem($id);
    }

    #[Route('', name: 'api_promotions_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createItem($request);
    }

    #[Route('/{id}', name: 'api_promotions_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->updateItem($request, $id);
    }

    #[Route('/{id}', name: 'api_promotions_patch', methods: ['PATCH'])]
    public function patch(Request $request, int $id): JsonResponse
    {
        return $this->patchItem($request, $id);
    }

    #[Route('/{id}', name: 'api_promotions_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteItem($id);
    }

    #[Route('/active', name: 'api_promotions_active', methods: ['GET'])]
    public function active(): JsonResponse
    {
        $promotions = $this->promotionService->findActivePromotions();

        return $this->json([
            'items' => $promotions
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-room-type/{roomTypeId}', name: 'api_promotions_by_room_type', methods: ['GET'])]
    public function byRoomType(int $roomTypeId): JsonResponse
    {
        $roomType = $this->entityManager->getReference('App\Entity\RoomType', $roomTypeId);
        $promotions = $this->promotionService->findByRoomType($roomType);

        return $this->json([
            'items' => $promotions
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-date-range', name: 'api_promotions_by_date_range', methods: ['GET'])]
    public function byDateRange(Request $request): JsonResponse
    {
        $startDate = new \DateTime($request->query->get('startDate'));
        $endDate = new \DateTime($request->query->get('endDate'));

        $promotions = $this->promotionService->findByDateRange($startDate, $endDate);

        return $this->json([
            'items' => $promotions
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/{id}/calculate-price', name: 'api_promotions_calculate_price', methods: ['GET'])]
    public function calculatePrice(int $id, Request $request): JsonResponse
    {
        $promotion = $this->promotionService->find($id);
        if (!$promotion) {
            return $this->json(['message' => 'Promotion not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $originalPrice = $request->query->get('price');
        if (!$originalPrice) {
            return $this->json(['message' => 'Price parameter is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $discountedPrice = $this->promotionService->calculateDiscountedPrice((float) $originalPrice, $promotion);

        return $this->json([
            'original_price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'savings' => $originalPrice - $discountedPrice
        ]);
    }
} 
