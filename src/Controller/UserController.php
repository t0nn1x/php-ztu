<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users')]
class UserController extends AbstractApiController
{
    public function __construct(
        UserService $userService,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        parent::__construct($userService, $serializer, $validator);
    }

    protected function getEntityClass(): string
    {
        return User::class;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return ['user:read'];
    }

    #[Route('', name: 'api_users_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only administrators can view all users')]
    public function list(Request $request): JsonResponse
    {
        return $this->getCollection($request);
    }

    #[Route('/{id}', name: 'api_users_get', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only administrators can view user details')]
    public function get(int $id): JsonResponse
    {
        return $this->getItem($id);
    }

    #[Route('', name: 'api_users_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only administrators can create users')]
    public function create(Request $request): JsonResponse
    {
        return $this->createItem($request);
    }

    #[Route('/{id}', name: 'api_users_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only administrators can update users')]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->updateItem($request, $id);
    }

    #[Route('/{id}', name: 'api_users_patch', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only administrators can modify users')]
    public function patch(Request $request, int $id): JsonResponse
    {
        return $this->patchItem($request, $id);
    }

    #[Route('/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only administrators can delete users')]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteItem($id);
    }
} 
