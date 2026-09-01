<?php

namespace App\Controllers\Auth;

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
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usuarios = new UsuarioModel();
        $usuario  = $usuarios->where('email', $email)->where('activo', 1)->first();

        if (! $usuario || ! password_verify($password ?? '', $usuario['password_hash'])) {
            return redirect()->to('/login')->with('error', 'Correo o contrasena incorrectos.');
        }

        $rol = model('RolModel')->find($usuario['rol_id']);

        session()->set([
            'usuario_id'     => $usuario['id'],
            'usuario_nombre' => $usuario['nombre'],
            'usuario_rol'    => $rol['nombre'] ?? null,
            'isLoggedIn'     => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('message', 'Sesion cerrada.');
    }
}
