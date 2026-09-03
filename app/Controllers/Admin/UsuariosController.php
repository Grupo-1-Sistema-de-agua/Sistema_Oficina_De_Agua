<?php

namespace App\Controllers\Admin;

use App\Constants\Roles;
use App\Controllers\BaseController;
use App\Models\RolModel;
use App\Models\UsuarioModel;

class UsuariosController extends BaseController
{
    public function index()
    {
        $usuarios = (new UsuarioModel())
            ->select('Tb_Usuarios.*, Tb_Roles.nombre AS rol_nombre')
            ->join('Tb_Roles', 'Tb_Roles.id = Tb_Usuarios.rol_id')
            ->orderBy('Tb_Usuarios.id', 'ASC')
            ->findAll();

        $roles = (new RolModel())
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('admin/usuarios', [
            'usuarios' => $usuarios,
            'roles'    => $roles,
        ]);
    }

    public function store()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $nombre = trim((string) $this->request->getPost('nombre'));
        $email  = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $confirmacion = (string) $this->request->getPost('confirm_password');
        $rolId = (int) $this->request->getPost('rol_id');

        if ($nombre === '' || $email === '' || $password === '' || $rolId <= 0) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'El correo no es valido.');
        }

        if (strlen($password) < 10) {
            return redirect()->back()->with('error', 'La contraseña debe tener al menos 10 caracteres.');
        }

        if ($password !== $confirmacion) {
            return redirect()->back()->with('error', 'La confirmación de la contraseña no coincide.');
        }

        $usuarioModel = new UsuarioModel();
        if ($usuarioModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'Ese correo ya esta registrado.');
        }

        $rol = (new RolModel())->find($rolId);
        if (! $rol) {
            return redirect()->back()->with('error', 'El rol seleccionado no existe.');
        }

        $usuarioModel->insert([
            'nombre'        => $nombre,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'activo'        => 1,
            'rol_id'        => $rolId,
        ]);

        return redirect()->to('/admin/usuarios')->with('message', 'Usuario creado correctamente.');
    }

    public function toggle()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $usuarioId = (int) $this->request->getPost('usuario_id');
        $usuario = (new UsuarioModel())->find($usuarioId);

        if (! $usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        if ((int) session()->get('usuario_id') === $usuarioId) {
            return redirect()->back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $nuevoEstado = (int) $usuario['activo'] === 1 ? 0 : 1;
        (new UsuarioModel())->update($usuarioId, ['activo' => $nuevoEstado]);

        $estadoTexto = $nuevoEstado === 1 ? 'activado' : 'desactivado';

        return redirect()->to('/admin/usuarios')->with('message', 'Usuario ' . $estadoTexto . ' correctamente.');
    }

    public function delete()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $usuarioId = (int) $this->request->getPost('usuario_id');
        $usuario = (new UsuarioModel())->find($usuarioId);

        if (! $usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        if ((int) session()->get('usuario_id') === $usuarioId) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        try {
            (new UsuarioModel())->delete($usuarioId);
            return redirect()->to('/admin/usuarios')->with('message', 'Usuario eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->to('/admin/usuarios')->with('error', 'No se puede eliminar este usuario porque hay registros relacionados.');
        }
    }
}
