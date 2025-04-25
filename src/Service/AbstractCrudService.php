<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class AbstractCrudService
{
    protected EntityManagerInterface $entityManager;
    protected EntityRepository $repository;
    protected string $entityClass;

    public function __construct(
        EntityManagerInterface $entityManager,
        string $entityClass
    ) {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
        $this->repository = $entityManager->getRepository($entityClass);
    }

    /**
     * Find entity by ID
     */
    public function find(int $id): ?object
    {
        return $this->repository->find($id);
    }

    /**
     * Find all entities
     * @return array<object>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Find entities by criteria
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return array<object>
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Find one entity by criteria
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?object
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Create a new entity
     */
    public function create(object $entity): object
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException(sprintf('Entity must be instance of %s', $this->entityClass));
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Update an existing entity
     */
    public function update(object $entity): object
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException(sprintf('Entity must be instance of %s', $this->entityClass));
        }

        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Delete an entity
     */
    public function delete(object $entity): void
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException(sprintf('Entity must be instance of %s', $this->entityClass));
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * Delete entity by ID
     */
    public function deleteById(int $id): void
    {
        $entity = $this->find($id);
        if ($entity) {
            $this->delete($entity);
        }
    }
} 
