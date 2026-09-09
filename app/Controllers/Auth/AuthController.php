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
            return redirect()->to('/login')->with('error', 'Debes completar correo y contrasena.');
        }

        $usuarios = new UsuarioModel();
        $usuario  = $usuarios->where('email', $email)->first();

        if (! $usuario || (int) ($usuario['activo'] ?? 0) !== 1) {
            return redirect()->to('/login')->with('error', 'No puedes iniciar sesion porque tu usuario fue desactivado temporalmente.');
        }

        if (! password_verify($password, (string) ($usuario['password_hash'] ?? ''))) {
            return redirect()->to('/login')->with('error', 'Correo o contrasena incorrectos.');
        }

        $rol = model('RolModel')->find($usuario['rol_id']);
        $rolNombre = Roles::normalize($rol['nombre'] ?? null);
        $passwordFingerprint = hash('sha256', (string) ($usuario['password_hash'] ?? ''));

        session()->regenerate();
        session()->set([
            'usuario_id'                 => $usuario['id'],
            'usuario_nombre'             => $usuario['nombre'],
            'usuario_rol'                => $rolNombre,
            'usuario_email'              => $usuario['email'],
            'usuario_password_fingerprint' => $passwordFingerprint,
            'isLoggedIn'                 => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('message', 'Sesion cerrada.');
    }
}
