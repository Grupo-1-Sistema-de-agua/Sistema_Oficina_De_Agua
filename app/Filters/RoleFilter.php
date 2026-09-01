<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Exige que el usuario en sesion tenga uno de los roles indicados.
 *
 * Uso en Routes.php, despues del filtro 'auth':
 *   ->add('...', [..., 'filter' => 'auth,role:administrador,secretaria'])
 *
 * Los nombres de rol deben coincidir con Tb_Roles.nombre (ver RolesSeeder).
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session       = session();
        $rolesPermitidos = $arguments ?? [];
        $rolActual       = $session->get('usuario_rol');

        if (empty($rolesPermitidos)) {
            return; // Sin argumentos, el filtro no restringe nada.
        }

        if (! in_array($rolActual, $rolesPermitidos, true)) {
            return redirect()->to('/dashboard')->with('error', 'No tienes permiso para acceder a esa seccion.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer despues de la respuesta.
    }
}
