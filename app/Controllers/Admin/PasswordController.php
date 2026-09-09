<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class PasswordController extends BaseController
{
    public function index()
    {
        $usuarios = (new UsuarioModel())
            ->select('Tb_Usuarios.*, Tb_Roles.nombre AS rol_nombre')
            ->join('Tb_Roles', 'Tb_Roles.id = Tb_Usuarios.rol_id')
            ->orderBy('Tb_Usuarios.id', 'ASC')
            ->findAll();

        return view('admin/password', [
            'usuarios' => $usuarios,
        ]);
    }

    public function update()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $usuarioId = (int) $this->request->getPost('usuario_id');
        $password = (string) $this->request->getPost('password');
        $confirmacion = (string) $this->request->getPost('confirm_password');

        if ($usuarioId <= 0 || $password === '') {
            return redirect()->back()->with('error', 'Debes seleccionar un usuario y escribir una nueva contraseña.');
        }

        if (strlen($password) < 10) {
            return redirect()->back()->with('error', 'La contraseña debe tener al menos 10 caracteres.');
        }

        if ($password !== $confirmacion) {
            return redirect()->back()->with('error', 'La confirmación de la contraseña no coincide.');
        }

        $usuario = (new UsuarioModel())->find($usuarioId);
        if (! $usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $usuarioModel = new UsuarioModel();
        $usuarioModel->update($usuarioId, [
            'password_hash' => $hashedPassword,
        ]);

        $session = session();
        if ((int) $session->get('usuario_id') === $usuarioId) {
            $session->set('usuario_password_fingerprint', hash('sha256', $hashedPassword));
        }

        return redirect()->to('/admin/password')->with('message', 'Contraseña actualizada correctamente.');
    }
}
