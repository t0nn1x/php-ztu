<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService extends AbstractCrudService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($entityManager, User::class);
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Create a new user with hashed password
     */
    public function createUser(string $email, string $password, string $firstName, string $lastName): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
        $user->setFirstName($firstName);
        $user->setLastName($lastName);

        return $this->create($user);
    }

    /**
     * Update user password
     */
    public function updatePassword(User $user, string $newPassword): User
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $newPassword)
        );

        return $this->update($user);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
} 
