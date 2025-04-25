<?php

namespace App\Controller;

use App\Service\AbstractCrudService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{
    protected AbstractCrudService $service;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;

    public function __construct(
        AbstractCrudService $service,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->service = $service;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    abstract protected function getEntityClass(): string;
    abstract protected function getDefaultSerializationGroups(): array;

    /**
     * Get collection of entities with optional filtering and pagination
     */
    protected function getCollection(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $orderBy = [];
        
        // Handle orderBy parameter if it exists
        $orderByField = $request->query->get('orderBy', '');
        $orderByDirection = $request->query->get('orderDir', 'ASC');
        if ($orderByField) {
            $orderBy[$orderByField] = strtoupper($orderByDirection);
        }

        $criteria = $request->query->all();

        // Remove pagination and ordering params from criteria
        unset($criteria['page'], $criteria['limit'], $criteria['orderBy'], $criteria['orderDir']);

        $offset = ($page - 1) * $limit;
        $entities = $this->service->findBy($criteria, $orderBy, $limit, $offset);
        $total = count($this->service->findBy($criteria));

        return $this->json([
            'items' => $entities,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ], Response::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    /**
     * Get single entity by ID
     */
    protected function getItem(int $id): JsonResponse
    {
        $entity = $this->service->find($id);

        if (!$entity) {
            return $this->json(['message' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($entity, Response::HTTP_OK, [], ['groups' => $this->getDefaultSerializationGroups()]);
    }

    /**
     * Create new entity
     */
    protected function createItem(Request $request): JsonResponse
    {
        $entityClass = $this->getEntityClass();
        $entity = $this->serializer->deserialize(
            $request->getContent(),
            $entityClass,
            'json'
        );

        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => (string) $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $entity = $this->service->create($entity);

        return $this->json(
            $entity,
            Response::HTTP_CREATED,
            [],
            ['groups' => $this->getDefaultSerializationGroups()]
        );
    }

    /**
     * Update existing entity
     */
    protected function updateItem(Request $request, int $id): JsonResponse
    {
        $existingEntity = $this->service->find($id);
        if (!$existingEntity) {
            return $this->json(['message' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $this->serializer->deserialize(
            $request->getContent(),
            $this->getEntityClass(),
            'json',
            ['object_to_populate' => $existingEntity]
        );

        $errors = $this->validator->validate($existingEntity);
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => (string) $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $entity = $this->service->update($existingEntity);

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            ['groups' => $this->getDefaultSerializationGroups()]
        );
    }

    /**
     * Delete entity
     */
    protected function deleteItem(int $id): JsonResponse
    {
        $entity = $this->service->find($id);
        if (!$entity) {
            return $this->json(['message' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $this->service->delete($entity);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Patch existing entity (partial update)
     */
    protected function patchItem(Request $request, int $id): JsonResponse
    {
        $existingEntity = $this->service->find($id);
        if (!$existingEntity) {
            return $this->json(['message' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $this->serializer->deserialize(
            $request->getContent(),
            $this->getEntityClass(),
            'json',
            [
                'object_to_populate' => $existingEntity,
                'ignored_attributes' => array_keys(array_diff_key(
                    get_object_vars($existingEntity),
                    $data
                ))
            ]
        );

        $errors = $this->validator->validate($existingEntity);
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Validation failed',
                'errors' => (string) $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $entity = $this->service->update($existingEntity);

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            ['groups' => $this->getDefaultSerializationGroups()]
        );
    }
}
