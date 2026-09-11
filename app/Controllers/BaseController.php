<?php

namespace App\Controllers;

use App\Constants\Roles;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    protected $session;
    protected $helpers = ['flash', 'csrf'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->iniciarSesionNativa();
    }

    /**
     * Inicia la sesión nativa de PHP para no utilizar sesiones de CI4.
     * Se llama una sola vez por petición, desde initController(), antes de que cualquier controlador
     * hijo ejecute su logica.
     */
    private function iniciarSesionNativa(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $esHttps = (bool) (
            ($_SERVER['HTTPS'] ?? '') !== '' && ($_SERVER['HTTPS'] ?? '') !== 'off'
        );

        session_set_cookie_params([
            'lifetime' => 7200,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $esHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    protected function estaLogueado(): bool
    {
        return isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
    }

    /**
     * Exige que haya sesion iniciada. Reemplaza a AuthFilter.
     *
     * Llamar al inicio de cualquier controlador protegido. Si no hay
     * sesion, o la sesion quedo invalida (usuario desactivado, o la
     * contrasena cambio en otro lugar mientras esta sesion seguia activa),
     * corta la ejecucion con un redirect nativo y detiene el script con
     * exit — el resto del codigo que llamo a esto nunca se ejecuta.
     */
    protected function requiereLogin(): void
    {
        if (! $this->estaLogueado()) {
            flash_set('error', 'Debes iniciar sesion para continuar.');
            header('Location: ' . site_url('login'));
            exit;
        }

        $usuario = (new UsuarioModel())->find($_SESSION['id_usuario'] ?? null);

        if (! $usuario || (int) ($usuario['activo'] ?? 0) !== 1) {
            $this->cerrarSesionInvalida('Tu sesion fue invalidada por falta de acceso.');
        }

        $huellaActual = hash('sha256', (string) ($usuario['password_hash'] ?? ''));
        if (($_SESSION['password_fingerprint'] ?? null) !== $huellaActual) {
            $this->cerrarSesionInvalida('Tu sesion expiro por un cambio de contrasena.');
        }
    }

    /**
     * Exige sesion iniciada Y que el rol actual este entre los permitidos.
     * Reemplaza a RoleFilter.
     *
     * @param string[] $rolesPermitidos ej. ['administrador'] o ['secretaria', 'administrador']
     */
    protected function requiereRol(array $rolesPermitidos): void
    {
        $this->requiereLogin();

        $rolActual       = Roles::normalize($_SESSION['rol'] ?? null);
        $rolesPermitidos = array_map(static fn ($rol) => Roles::normalize($rol), $rolesPermitidos);

        if (! in_array($rolActual, $rolesPermitidos, true)) {
            flash_set('error', 'No tienes permiso para acceder a esa seccion.');
            header('Location: ' . site_url('dashboard'));
            exit;
        }
    }

    /**
     * Destruye una sesion que ya no es valida y redirige al login con un
     * mensaje explicando por que. Se usa desde requiereLogin().
     */
    private function cerrarSesionInvalida(string $mensaje): void
    {
        $_SESSION = [];
        session_destroy();
        session_start();
        flash_set('error', $mensaje);
        header('Location: ' . site_url('login'));
        exit;
    }
}
