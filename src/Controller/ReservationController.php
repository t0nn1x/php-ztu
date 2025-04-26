<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Service\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/reservations')]
class ReservationController extends AbstractApiController
{
    private ReservationService $reservationService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ReservationService $reservationService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($reservationService, $serializer, $validator);
        $this->reservationService = $reservationService;
        $this->entityManager = $entityManager;
    }

    protected function getEntityClass(): string
    {
        return Reservation::class;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return ['reservation:read'];
    }

    #[Route('', name: 'api_reservations_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        return $this->getCollection($request);
    }

    #[Route('/{id}', name: 'api_reservations_get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->getItem($id);
    }

    #[Route('', name: 'api_reservations_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createItem($request);
    }

    #[Route('/{id}', name: 'api_reservations_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->updateItem($request, $id);
    }

    #[Route('/{id}', name: 'api_reservations_patch', methods: ['PATCH'])]
    public function patch(Request $request, int $id): JsonResponse
    {
        return $this->patchItem($request, $id);
    }

    #[Route('/{id}', name: 'api_reservations_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteItem($id);
    }

    #[Route('/by-user/{userId}', name: 'api_reservations_by_user', methods: ['GET'])]
    public function byUser(int $userId): JsonResponse
    {
        $user = $this->entityManager->getReference('App\Entity\User', $userId);
        $reservations = $this->reservationService->findByUser($user);

        return $this->json([
            'items' => $reservations
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-date-range', name: 'api_reservations_by_date_range', methods: ['GET'])]
    public function byDateRange(Request $request): JsonResponse
    {
        $startDate = new \DateTime($request->query->get('startDate'));
        $endDate = new \DateTime($request->query->get('endDate'));

        $reservations = $this->reservationService->findByDateRange($startDate, $endDate);

        return $this->json([
            'items' => $reservations
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/by-status/{status}', name: 'api_reservations_by_status', methods: ['GET'])]
    public function byStatus(string $status): JsonResponse
    {
        $reservations = $this->reservationService->findByStatus($status);

        return $this->json([
            'items' => $reservations
        ], JsonResponse::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    #[Route('/{id}/calculate-price', name: 'api_reservations_calculate_price', methods: ['GET'])]
    public function calculatePrice(int $id): JsonResponse
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            return $this->json(['message' => 'Reservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $total = $this->reservationService->calculateTotalPrice($reservation);

        return $this->json([
            'total' => $total
        ]);
    }
} 
