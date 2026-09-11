<?php

namespace App\Constants;

/**
 * Nombres de rol validos en el sistema.
 */
class Roles
{
    public const ADMINISTRADOR = 'administrador';
    public const SECRETARIA    = 'secretaria';
    public const LECTOR        = 'lector';

    public static function normalize(?string $rol): ?string
    {
        if ($rol === null) {
            return null;
        }

        return strtolower(trim($rol));
    }

    public static function isAdmin(?string $rol): bool
    {
        return self::normalize($rol) === self::ADMINISTRADOR;
    }
}