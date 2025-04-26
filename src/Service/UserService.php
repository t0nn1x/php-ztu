<?php

namespace App\Service;

use App\Entity\User;
use App\Security\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService extends AbstractEntityService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($entityManager);
        $this->passwordHasher = $passwordHasher;
    }

    protected function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * Create a new user with hashed password
     */
    public function createUser(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        array $roles = [RoleEnum::ROLE_CLIENT->value]
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRoles($roles);
        $user->setIsActive(true);

        $this->save($user);

        return $user;
    }

    /**
     * Create an admin user
     */
    public function createAdmin(string $email, string $password, string $firstName, string $lastName): User
    {
        return $this->createUser($email, $password, $firstName, $lastName, [RoleEnum::ROLE_ADMIN->value]);
    }

    /**
     * Create a manager user
     */
    public function createManager(string $email, string $password, string $firstName, string $lastName): User
    {
        return $this->createUser($email, $password, $firstName, $lastName, [RoleEnum::ROLE_MANAGER->value]);
    }

    /**
     * Create a client user
     */
    public function createClient(string $email, string $password, string $firstName, string $lastName): User
    {
        return $this->createUser($email, $password, $firstName, $lastName, [RoleEnum::ROLE_CLIENT->value]);
    }

    /**
     * Update user password
     */
    public function updatePassword(User $user, string $newPassword): User
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $newPassword)
        );

        $this->save($user);

        return $user;
    }

    /**
     * Add role to user
     */
    public function addRole(User $user, RoleEnum $role): User
    {
        $roles = $user->getRoles();
        $roles[] = $role->value;
        $user->setRoles(array_unique($roles));

        $this->save($user);

        return $user;
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, RoleEnum $role): User
    {
        $roles = array_diff($user->getRoles(), [$role->value]);
        $user->setRoles($roles);

        $this->save($user);

        return $user;
    }

    /**
     * Check if user has role
     */
    public function hasRole(User $user, RoleEnum $role): bool
    {
        return in_array($role->value, $user->getRoles());
    }

    /**
     * Find users by role
     */
    public function findByRole(RoleEnum $role): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role->value . '"%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
} 
