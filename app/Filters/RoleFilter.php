<?php

namespace App\Filters;

use App\Constants\Roles;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Exige que el usuario en sesion tenga uno de los roles indicados.
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $rolesPermitidos = $arguments ?? [];
        $rolActual = Roles::normalize($session->get('usuario_rol'));

        if (empty($rolesPermitidos)) {
            return;
        }

        $rolesPermitidos = array_map(static fn ($rol) => Roles::normalize($rol), $rolesPermitidos);

        if (! in_array($rolActual, $rolesPermitidos, true)) {
            return redirect()->to('/dashboard')->with('error', 'No tienes permiso para acceder a esa seccion.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer despues de la respuesta.
    }
}
