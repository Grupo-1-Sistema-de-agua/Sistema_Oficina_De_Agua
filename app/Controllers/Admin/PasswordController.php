<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PasswordController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['administrador']);
    }

    public function edit(int $usuarioId)
    {
        $usuario = (new UsuarioModel())->find($usuarioId);

        if (! $usuario) {
            flash_set('error', 'Usuario no encontrado.');
            return redirect()->to('/admin/usuarios');
        }

        return view('admin/password', [
            'usuario' => $usuario,
        ]);
    }

    public function update(int $usuarioId)
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/admin/usuarios');
        }

        $usuario = (new UsuarioModel())->find($usuarioId);
        if (! $usuario) {
            flash_set('error', 'Usuario no encontrado.');
            return redirect()->to('/admin/usuarios');
        }

        $password = (string) $this->request->getPost('password');
        $confirmacion = (string) $this->request->getPost('confirm_password');

        if ($password === '') {
            flash_set('error', 'Debes escribir una nueva contraseña.');
            return redirect()->back();
        }

        if (strlen($password) < 10) {
            flash_set('error', 'La contraseña debe tener al menos 10 caracteres.');
            return redirect()->back();
        }

        if ($password !== $confirmacion) {
            flash_set('error', 'La confirmación de la contraseña no coincide.');
            return redirect()->back();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        (new UsuarioModel())->update($usuarioId, [
            'password_hash' => $hashedPassword,
        ]);

        // Si el usuario se cambio la contrasena a si mismo, actualizamos la
        // huella en su propia sesion para que no quede invalidada de inmediato
        // por requiereLogin() (que compara esta huella contra el hash actual).
        if ((int) ($_SESSION['id_usuario'] ?? 0) === $usuarioId) {
            $_SESSION['password_fingerprint'] = hash('sha256', $hashedPassword);
        }

        flash_set('message', 'Contraseña de ' . $usuario['nombre'] . ' actualizada correctamente.');
        return redirect()->to('/admin/usuarios');
    }
}