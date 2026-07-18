<?php

namespace Controllers;

use Core\Autenticacion;
use Core\Controlador;
use Core\CSRF;
use Core\HashPassword;
use Helpers\Sanitizador;
use Helpers\Validador;
use Models\Usuario;
use Models\Colaborador;

class AuthController extends Controlador
{
    private $usuarioModel;
    private $colaboradorModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
        $this->colaboradorModel = new Colaborador();
    }

    public function showLogin()
    {
        if (!isset($_GET['forcer']) || $_GET['forcer'] != 1) {
            if (Autenticacion::estaLogueado()) {
                $rol = strtolower($_SESSION['user_rol'] ?? '');
                if ($rol === 'supervisor') {
                    $this->redirigir('/supervisor/dashboard');
                } elseif ($rol === 'agente') {
                    $this->redirigir('/agente/dashboard');
                } else {
                    $this->redirigir('/admin/dashboard');
                }
                return;
            }

            if (isset($_SESSION['colaborador_id'])) {
                $this->redirigir('/public/dashboard');
                return;
            }
        }

        $this->vista('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function authenticate()
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Token de seguridad inválido';
            $this->redirigir('/login');
            return;
        }

        $email = Sanitizador::sanitizarEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            $this->redirigir('/login');
            return;
        }

        try {
            Validador::validarEmail($email);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/login');
            return;
        }

        // ============================================================
        // Un único formulario de login para todo el sistema.
        // Primero se busca el correo entre el personal (usuarios:
        // admin/agente/supervisor). Si no existe ahí, se intenta como
        // colaborador (clientes que crean tickets).
        // ============================================================
        if ($this->usuarioModel->obtenerPorEmail($email)) {
            $this->autenticarPersonal($email, $password);
            return;
        }

        if ($this->colaboradorModel->obtenerPorEmail($email)) {
            $this->autenticarColaborador($email, $password);
            return;
        }

        $_SESSION['error'] = 'Credenciales incorrectas';
        $this->redirigir('/login');
    }

    private function autenticarPersonal($email, $password)
    {
        $resultado = Autenticacion::login($email, $password);

        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];

            $rol = strtolower($_SESSION['user_rol'] ?? '');
            if ($rol === 'supervisor') {
                $this->redirigir('/supervisor/dashboard');
            } elseif ($rol === 'agente') {
                $this->redirigir('/agente/dashboard');
            } else {
                $this->redirigir('/admin/dashboard');
            }
            return;
        }

        $_SESSION['error'] = $resultado['message'];
        $this->redirigir('/login');
    }

    private function autenticarColaborador($email, $password)
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            // Control de intentos en sesión (mismo comportamiento que tenía
            // el portal de colaboradores)
            $clave_intentos = 'intentos_colaborador_' . $email;
            if (!isset($_SESSION[$clave_intentos])) {
                $_SESSION[$clave_intentos] = 0;
            }

            if ($_SESSION[$clave_intentos] >= 3) {
                $clave_bloqueo = 'bloqueo_colaborador_' . $email;
                if (!isset($_SESSION[$clave_bloqueo])) {
                    $_SESSION[$clave_bloqueo] = time() + 900; // 15 minutos
                }

                if (time() < $_SESSION[$clave_bloqueo]) {
                    $tiempo_restante = ceil(($_SESSION[$clave_bloqueo] - time()) / 60);
                    throw new \Exception("Cuenta temporalmente bloqueada. Intente nuevamente en $tiempo_restante minutos.");
                } else {
                    $_SESSION[$clave_intentos] = 0;
                    unset($_SESSION[$clave_bloqueo]);
                }
            }

            if (empty($password)) {
                throw new \Exception('La contraseña es obligatoria');
            }

            $colaborador = $this->colaboradorModel->obtenerPorEmail($email);

            if (!$colaborador) {
                $_SESSION[$clave_intentos]++;
                $this->registrarIntento($email, $ip, false, 'Usuario no encontrado');
                throw new \Exception('Credenciales incorrectas');
            }

            if (!$colaborador['activo']) {
                throw new \Exception('Cuenta desactivada. Contacte al administrador.');
            }

            if (!(new HashPassword())->verificar($password, $colaborador['password'])) {
                $_SESSION[$clave_intentos]++;
                $this->registrarIntento($email, $ip, false, 'Contraseña incorrecta');
                throw new \Exception('Credenciales incorrectas');
            }

            $_SESSION[$clave_intentos] = 0;
            unset($_SESSION[$clave_bloqueo]);
            $this->registrarIntento($email, $ip, true, 'Login exitoso');

            $_SESSION['colaborador_id'] = $colaborador['id'];
            $_SESSION['colaborador_nombre'] = $colaborador['primer_nombre'] . ' ' . $colaborador['primer_apellido'];
            $_SESSION['colaborador_email'] = $colaborador['email'];
            $_SESSION['colaborador_foto'] = $colaborador['foto_perfil'] ?? null;
            $_SESSION['colaborador_inicial'] = strtoupper(substr($colaborador['primer_nombre'], 0, 1));

            $_SESSION['success'] = 'Bienvenido ' . $_SESSION['colaborador_nombre'];
            $this->redirigir('/public/perfil');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/login');
        }
    }

    private function registrarIntento($email, $ip, $exitoso, $mensaje)
    {
        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->prepare("INSERT INTO logs_login (email, ip, exitoso, mensaje) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $ip, $exitoso, $mensaje]);
    }

    public function logout()
    {
        Autenticacion::logout();
        $_SESSION['success'] = 'Sesión cerrada correctamente';
        $this->redirigir('/login');
    }
}
