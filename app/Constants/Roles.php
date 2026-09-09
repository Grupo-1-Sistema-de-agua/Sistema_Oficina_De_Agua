<?php

namespace App\Constants;

/**
 * Nombres de rol validos en el sistema.
 *
 * Se mantienen los nombres actuales del proyecto, y tambien se aceptan
 * los aliases usados en la version anterior del modulo de autenticacion.
 */
class Roles
{
    public const ADMINISTRADOR = 'administrador';
    public const ADMIN         = 'admin';
    public const SECRETARIA    = 'secretaria';
    public const LECTOR        = 'lector';
    public const OPERADOR      = 'operador';
    public const CONSULTA      = 'consulta';

    public static function normalize(?string $rol): ?string
    {
        if ($rol === null) {
            return null;
        }

        $rol = strtolower(trim($rol));

        $aliases = [
            'admin'        => self::ADMIN,
            'administrador' => self::ADMINISTRADOR,
            'operador'     => self::OPERADOR,
            'consulta'     => self::CONSULTA,
            'secretaria'   => self::SECRETARIA,
            'lector'      => self::LECTOR,
        ];

        return $aliases[$rol] ?? $rol;
    }

    public static function isAdmin(?string $rol): bool
    {
        $rol = self::normalize($rol);

        return in_array($rol, [self::ADMIN, self::ADMINISTRADOR], true);
    }
}
