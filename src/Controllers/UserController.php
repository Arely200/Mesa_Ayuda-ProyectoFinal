<?php

namespace Controllers;

use Core\Controlador;
use Core\Autenticacion;
use Core\CSRF;
use Core\HashPassword;
use Helpers\Sanitizador;
use Helpers\Validador;
use Models\Usuario;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class UserController extends Controlador
{
    private $usuarioModel;

    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('usuarios', 'ver');
        $this->usuarioModel = new Usuario();
    }

 public function index()
{
   
  $pagina = (int)($_GET['pagina'] ?? 1);
    $porPagina = (int)($_GET['porPagina'] ?? 5);
    
    $usuarios = $this->usuarioModel->obtenerPaginados($pagina, $porPagina);
    $total = $this->usuarioModel->contarTodos();
    $totalPaginas = ceil($total / $porPagina);
    
    $this->vista('admin/usuarios/index', [
        'usuarios' => $usuarios,
        'total' => $total,
        'pagina' => $pagina,
        'totalPaginas' => $totalPaginas,
        'porPagina' => $porPagina,
        'titulo' => 'Gestión de Usuarios'
    ]);
}


    public function guardar()
    {
        RolMiddleware::checkPermission('usuarios', 'crear');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $nombre = Sanitizador::sanitizarString($_POST['nombre'] ?? '');
            $email = Sanitizador::sanitizarEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'agente';

            Validador::validarRequerido(['nombre' => $nombre, 'email' => $email, 'password' => $password]);
            Validador::validarEmail($email);
            Validador::validarPassword($password);

            // Verificar si el email ya existe
            $existe = $this->usuarioModel->obtenerPorEmail($email);
            if ($existe) {
                $_SESSION['error'] = 'El correo ya está registrado';
                $this->redirigir('/admin/usuarios/crear');
                return;
            }

            $hash = (new HashPassword())->generar($password);

            $datos = [
                'nombre' => $nombre,
                'email' => $email,
                'password' => $hash,
                'rol' => $rol
            ];

            $this->usuarioModel->crearUsuario($datos);

            $_SESSION['success'] = 'Usuario creado correctamente';
            $this->redirigir('/admin/usuarios');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/usuarios/crear');
        }
    }

    public function editar($id)
    {
        RolMiddleware::checkPermission('usuarios', 'editar');
        $usuario = $this->usuarioModel->encontrarPorId($id);
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirigir('/admin/usuarios');
            return;
        }

        $this->vista('admin/usuarios/editar', [
            'usuario' => $usuario,
            'titulo' => 'Editar Usuario',
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function actualizar($id)
    {
        RolMiddleware::checkPermission('usuarios', 'editar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $nombre = Sanitizador::sanitizarString($_POST['nombre'] ?? '');
            $email = Sanitizador::sanitizarEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'agente';

            Validador::validarRequerido(['nombre' => $nombre, 'email' => $email]);
            Validador::validarEmail($email);

            if (!empty($password)) {
                Validador::validarPassword($password);
                $hash = (new HashPassword())->generar($password);
            }

            $datos = [
                'nombre' => $nombre,
                'email' => $email,
                'rol' => $rol
            ];

            if (isset($hash)) {
                $datos['password'] = $hash;
            }

            $this->usuarioModel->actualizarUsuario($id, $datos);

            $_SESSION['success'] = 'Usuario actualizado correctamente';
            $this->redirigir('/admin/usuarios');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/usuarios/editar/' . $id);
        }
    }

    public function eliminar($id)
    {
        RolMiddleware::checkPermission('usuarios', 'eliminar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            // No eliminar al admin principal
            $usuario = $this->usuarioModel->encontrarPorId($id);
            if ($usuario && $usuario['email'] === 'admin@mesaayuda.com') {
                $_SESSION['error'] = 'No se puede eliminar al administrador principal';
                $this->redirigir('/admin/usuarios');
                return;
            }

            $this->usuarioModel->eliminarUsuario($id);
            $_SESSION['success'] = 'Usuario eliminado correctamente';

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirigir('/admin/usuarios');
    }
    // ============================================================
// MOSTRAR FORMULARIO PARA CREAR USUARIO
// ============================================================
public function crear()
{
    RolMiddleware::checkPermission('usuarios', 'crear');
    $this->vista('admin/usuarios/crear', [
        'titulo' => 'Nuevo Usuario',
        'csrf_token' => CSRF::generarToken()
    ]);
}
}
