<?php

namespace App\Filters;

use App\Models\UsuarioModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Exige que haya un usuario con sesion iniciada.
 * Si no la hay, redirige al login.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $usuarioId = $session->get('usuario_id');

        if (! $usuarioId) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesion para continuar.');
        }

        $usuario = (new UsuarioModel())->find($usuarioId);
        if (! $usuario || (int) ($usuario['activo'] ?? 0) !== 1) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Tu sesion fue invalidada por falta de acceso.');
        }

        $fingerprint = $session->get('usuario_password_fingerprint');
        if ($fingerprint && $fingerprint !== hash('sha256', (string) ($usuario['password_hash'] ?? ''))) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Tu sesion expiró por un cambio de contrasena.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer despues de la respuesta.
    }
}
