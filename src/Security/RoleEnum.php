<?php

namespace App\Security;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_MANAGER = 'ROLE_MANAGER';
    case ROLE_CLIENT = 'ROLE_CLIENT';
    case ROLE_USER = 'ROLE_USER';

    /**
     * Get all roles that are higher or equal to the given role
     */
    public static function getHigherRoles(self $role): array
    {
        return match ($role) {
            self::ROLE_ADMIN => [self::ROLE_ADMIN->value],
            self::ROLE_MANAGER => [self::ROLE_ADMIN->value, self::ROLE_MANAGER->value],
            self::ROLE_CLIENT => [self::ROLE_ADMIN->value, self::ROLE_MANAGER->value, self::ROLE_CLIENT->value],
            self::ROLE_USER => [self::ROLE_ADMIN->value, self::ROLE_MANAGER->value, self::ROLE_CLIENT->value, self::ROLE_USER->value],
        };
    }

    /**
     * Get all available roles
     */
    public static function getAllRoles(): array
    {
        return [
            self::ROLE_ADMIN->value,
            self::ROLE_MANAGER->value,
            self::ROLE_CLIENT->value,
            self::ROLE_USER->value,
        ];
    }
} 
