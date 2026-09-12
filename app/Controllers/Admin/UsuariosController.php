<?php

namespace App\Controllers\Admin;

use App\Constants\Roles;
use App\Controllers\BaseController;
use App\Models\RolModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class UsuariosController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['administrador']);
    }

    public function index()
    {
        $usuarios = (new UsuarioModel())
            ->select('Tb_Usuarios.*, Tb_Roles.nombre AS rol_nombre')
            ->join('Tb_Roles', 'Tb_Roles.id = Tb_Usuarios.rol_id')
            ->orderBy('Tb_Usuarios.id', 'ASC')
            ->findAll();

        return view('admin/usuarios', [
            'usuarios' => $usuarios,
        ]);
    }

    public function nuevo()
    {
        $roles = (new RolModel())
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('admin/usuario_nuevo', [
            'roles' => $roles,
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
            flash_set('error', 'Todos los campos son obligatorios.');
            return redirect()->back()->withInput();
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'El correo no es valido.');
            return redirect()->back()->withInput();
        }

        if (strlen($password) < 10) {
            flash_set('error', 'La contraseña debe tener al menos 10 caracteres.');
            return redirect()->back()->withInput();
        }

        if ($password !== $confirmacion) {
            flash_set('error', 'La confirmación de la contraseña no coincide.');
            return redirect()->back()->withInput();
        }

        $usuarioModel = new UsuarioModel();
        if ($usuarioModel->where('email', $email)->first()) {
            flash_set('error', 'Ese correo ya esta registrado.');
            return redirect()->back()->withInput();
        }

        $rol = (new RolModel())->find($rolId);
        if (! $rol) {
            flash_set('error', 'El rol seleccionado no existe.');
            return redirect()->back()->withInput();
        }

        $usuarioModel->insert([
            'nombre'        => $nombre,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'activo'        => 1,
            'rol_id'        => $rolId,
        ]);

        flash_set('message', 'Usuario creado correctamente.');
        return redirect()->to('/admin/usuarios');
    }

    public function toggle()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $usuarioId = (int) $this->request->getPost('usuario_id');
        $usuario = (new UsuarioModel())->find($usuarioId);

        if (! $usuario) {
            flash_set('error', 'Usuario no encontrado.');
            return redirect()->back();
        }

        if ((int) ($_SESSION['id_usuario'] ?? 0) === $usuarioId) {
            flash_set('error', 'No puedes desactivar tu propia cuenta.');
            return redirect()->back();
        }

        $nuevoEstado = (int) $usuario['activo'] === 1 ? 0 : 1;
        (new UsuarioModel())->update($usuarioId, ['activo' => $nuevoEstado]);

        $estadoTexto = $nuevoEstado === 1 ? 'activado' : 'desactivado';

        flash_set('message', 'Usuario ' . $estadoTexto . ' correctamente.');
        return redirect()->to('/admin/usuarios');
    }

    public function delete()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $usuarioId = (int) $this->request->getPost('usuario_id');
        $usuario = (new UsuarioModel())->find($usuarioId);

        if (! $usuario) {
            flash_set('error', 'Usuario no encontrado.');
            return redirect()->back();
        }

        if ((int) ($_SESSION['id_usuario'] ?? 0) === $usuarioId) {
            flash_set('error', 'No puedes eliminar tu propia cuenta.');
            return redirect()->back();
        }

        try {
            (new UsuarioModel())->delete($usuarioId);
            flash_set('message', 'Usuario eliminado correctamente.');
            return redirect()->to('/admin/usuarios');
        } catch (\Throwable $e) {
            flash_set('error', 'No se puede eliminar este usuario porque hay registros relacionados.');
            return redirect()->to('/admin/usuarios');
        }
    }
}