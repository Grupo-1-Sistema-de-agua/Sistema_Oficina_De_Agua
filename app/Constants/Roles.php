<?php

namespace App\Constants;

/**
 * Nombres de rol validos en el sistema (deben coincidir exactamente
 * con los valores de Tb_Roles.nombre sembrados por RolesSeeder).
 *
 * Usenlos en vez de escribir el string a mano, por ejemplo:
 *   'filter' => 'auth,role:' . Roles::ADMINISTRADOR
 */
class Roles
{
    public const ADMINISTRADOR = 'administrador';
    public const SECRETARIA    = 'secretaria';
    public const LECTOR        = 'lector';
}
