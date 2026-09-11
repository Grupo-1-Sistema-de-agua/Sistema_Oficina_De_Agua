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
            flash_set('error', 'Debes seleccionar un usuario y escribir una nueva contraseña.');
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

        $usuario = (new UsuarioModel())->find($usuarioId);
        if (! $usuario) {
            flash_set('error', 'Usuario no encontrado.');
            return redirect()->back();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $usuarioModel = new UsuarioModel();
        $usuarioModel->update($usuarioId, [
            'password_hash' => $hashedPassword,
        ]);

        // Si el usuario se cambio la contrasena a si mismo, actualizamos la
        // huella en su propia sesion para que no quede invalidada de inmediato
        // por requiereLogin() (que compara esta huella contra el hash actual).
        if ((int) ($_SESSION['id_usuario'] ?? 0) === $usuarioId) {
            $_SESSION['password_fingerprint'] = hash('sha256', $hashedPassword);
        }

        flash_set('message', 'Contraseña actualizada correctamente.');
        return redirect()->to('/admin/password');
    }
}