<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractEntityService
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract protected function getEntityClass(): string;

    public function find(int $id): ?object
    {
        return $this->entityManager->getRepository($this->getEntityClass())->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository($this->getEntityClass())->findAll();
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->entityManager->getRepository($this->getEntityClass())->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria): ?object
    {
        return $this->entityManager->getRepository($this->getEntityClass())->findOneBy($criteria);
    }

    public function save(object $entity, bool $flush = true): void
    {
        $this->entityManager->persist($entity);
        
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(object $entity, bool $flush = true): void
    {
        $this->entityManager->remove($entity);
        
        if ($flush) {
            $this->entityManager->flush();
        }
    }
} 
