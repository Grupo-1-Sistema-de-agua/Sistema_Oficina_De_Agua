<?php
namespace App\Controllers\Auth;

use App\Constants\Roles;
use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function procesarLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            flash_set('error', 'Debes completar correo y contraseña.');
            return redirect()->to('/login');
        }

        $usuarios = new UsuarioModel();
        $usuario  = $usuarios->where('email', $email)->first();

        if (! $usuario || (int) ($usuario['activo'] ?? 0) !== 1) {
            flash_set('error', 'No puedes iniciar sesión porque tu usuario fue desactivado temporalmente.');
            return redirect()->to('/login');
        }

        if (! password_verify($password, (string) ($usuario['password_hash'] ?? ''))) {
            flash_set('error', 'Correo o contraseña incorrectos.');
            return redirect()->to('/login');
        }

        $rol       = model('RolModel')->find($usuario['rol_id']);
        $rolNombre = Roles::normalize($rol['nombre'] ?? null);

        session_regenerate_id(true);

        $_SESSION['logueado']   = true;
        $_SESSION['id_usuario'] = (int) $usuario['id'];
        $_SESSION['nombre']     = $usuario['nombre'];
        $_SESSION['rol']        = $rolNombre;
        $_SESSION['email']      = $usuario['email'];
        $_SESSION['password_fingerprint'] = hash('sha256', (string) ($usuario['password_hash'] ?? ''));

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        session_start();
        flash_set('message', 'Sesion cerrada.');

        return redirect()->to('/login');
    }
}