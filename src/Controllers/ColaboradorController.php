<?php

namespace Controllers;

use Core\Controlador;
use Core\CSRF;
use Core\HashPassword;
use Helpers\Sanitizador;
use Helpers\Validador;
use Helpers\ValidacionException;
use Models\Colaborador;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class ColaboradorController extends Controlador
{
    private $colaboradorModel;

    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('colaboradores', 'ver');
        $this->colaboradorModel = new Colaborador();
    }

    public function index()
    {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 5;

        $colaboradores = $this->colaboradorModel->obtenerPaginados($pagina, $porPagina);
        $total = $this->colaboradorModel->contarTodos();
        $totalPaginas = ceil($total / $porPagina);

        $this->vista('admin/colaboradores/index', [
            'colaboradores' => $colaboradores,
            'total' => $total,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina,
            'titulo' => 'Gestión de Colaboradores'
        ]);
    }

    public function crear()
    {
        RolMiddleware::checkPermission('colaboradores', 'crear');

        // Si venimos de un error de validación, repoblamos con lo que
        // el usuario había escrito, en vez de mostrar el formulario vacío.
        $viejo = $_SESSION['old_input'] ?? [];
        $campoError = $_SESSION['campo_error'] ?? null;
        unset($_SESSION['old_input'], $_SESSION['campo_error']);

        $this->vista('admin/colaboradores/crear', [
            'viejo' => $viejo,
            'campoError' => $campoError,
            'titulo' => 'Nuevo Colaborador',
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function guardar()
    {
        RolMiddleware::checkPermission('colaboradores', 'crear');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $primer_nombre = Sanitizador::sanitizarString($_POST['primer_nombre'] ?? '');
            $segundo_nombre = Sanitizador::sanitizarString($_POST['segundo_nombre'] ?? '');
            $primer_apellido = Sanitizador::sanitizarString($_POST['primer_apellido'] ?? '');
            $segundo_apellido = Sanitizador::sanitizarString($_POST['segundo_apellido'] ?? '');
            $sexo = $_POST['sexo'] ?? 'Otro';
            $identificacion = Sanitizador::sanitizarString($_POST['identificacion'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $email = Sanitizador::sanitizarEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            Validador::validarRequerido([
                'primer_nombre' => $primer_nombre,
                'primer_apellido' => $primer_apellido,
                'identificacion' => $identificacion,
                'fecha_nacimiento' => $fecha_nacimiento,
                'email' => $email,
                'password' => $password
            ]);

            Validador::validarEmail($email);
            Validador::validarPassword($password);
            Validador::validarIdentificacion($identificacion);

            $existe = $this->colaboradorModel->obtenerPorIdentificacion($identificacion);
            if ($existe) {
                throw new ValidacionException('La identificación ya está registrada', 'identificacion');
            }

            $existeEmail = $this->colaboradorModel->obtenerPorEmail($email);
            if ($existeEmail) {
                throw new ValidacionException('El correo ya está registrado', 'email');
            }

            $hash = (new HashPassword())->generar($password);

            // Procesar foto de perfil
            $foto_perfil = 'default-avatar.png';
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $tipoReal = mime_content_type($_FILES['foto_perfil']['tmp_name']);
                if (!in_array($tipoReal, $tiposPermitidos)) {
                    throw new ValidacionException('Solo se permiten imágenes JPG, PNG, GIF o WEBP', 'foto_perfil');
                }
                if ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
                    throw new ValidacionException('La imagen no puede superar 2MB', 'foto_perfil');
                }
                $extension = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $nombre = time() . '_' . uniqid() . '.' . $extension;
                $ruta = UPLOADS_PATH . '/avatars/' . $nombre;

                if (!is_dir(UPLOADS_PATH . '/avatars')) {
                    mkdir(UPLOADS_PATH . '/avatars', 0777, true);
                }

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta)) {
                    $foto_perfil = $nombre;
                }
            }

            $datos = [
                'primer_nombre' => $primer_nombre,
                'segundo_nombre' => $segundo_nombre,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'sexo' => $sexo,
                'identificacion' => $identificacion,
                'fecha_nacimiento' => $fecha_nacimiento,
                'email' => $email,
                'password' => $hash,
                'foto_perfil' => $foto_perfil
            ];

            $this->colaboradorModel->crearColaborador($datos);

            $_SESSION['success'] = 'Colaborador creado correctamente';
            $this->redirigir('/admin/colaboradores');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['campo_error'] = $e instanceof ValidacionException ? $e->campo : null;
            // Guardamos lo que el usuario ya había escrito (menos password
            // y token) para repoblar el formulario, no perder su trabajo.
            $viejo = $_POST;
            unset($viejo['password'], $viejo['csrf_token']);
            $_SESSION['old_input'] = $viejo;
            $this->redirigir('/admin/colaboradores/crear');
        }
    }

    public function editar($id)
    {
        RolMiddleware::checkPermission('colaboradores', 'editar');
        $colaborador = $this->colaboradorModel->encontrarPorId($id);
        if (!$colaborador) {
            $_SESSION['error'] = 'Colaborador no encontrado';
            $this->redirigir('/admin/colaboradores');
            return;
        }

        // Si venimos de un error de validación, repoblamos con lo que
        // el usuario había escrito, en vez de los datos originales de la BD.
        if (!empty($_SESSION['old_input'])) {
            $colaborador = array_merge($colaborador, $_SESSION['old_input']);
        }
        $campoError = $_SESSION['campo_error'] ?? null;
        unset($_SESSION['old_input'], $_SESSION['campo_error']);

        $this->vista('admin/colaboradores/editar', [
            'colaborador' => $colaborador,
            'campoError' => $campoError,
            'titulo' => 'Editar Colaborador',
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function actualizar($id)
    {
        RolMiddleware::checkPermission('colaboradores', 'editar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $primer_nombre = Sanitizador::sanitizarString($_POST['primer_nombre'] ?? '');
            $segundo_nombre = Sanitizador::sanitizarString($_POST['segundo_nombre'] ?? '');
            $primer_apellido = Sanitizador::sanitizarString($_POST['primer_apellido'] ?? '');
            $segundo_apellido = Sanitizador::sanitizarString($_POST['segundo_apellido'] ?? '');
            $sexo = $_POST['sexo'] ?? 'Otro';
            $identificacion = Sanitizador::sanitizarString($_POST['identificacion'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $email = Sanitizador::sanitizarEmail($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            Validador::validarRequerido([
                'primer_nombre' => $primer_nombre,
                'primer_apellido' => $primer_apellido,
                'identificacion' => $identificacion,
                'fecha_nacimiento' => $fecha_nacimiento,
                'email' => $email
            ]);

            Validador::validarEmail($email);
            Validador::validarIdentificacion($identificacion);

            if (!empty($password)) {
                Validador::validarPassword($password);
                $hash = (new HashPassword())->generar($password);
            }

            // Obtener colaborador actual para mantener foto
            $colaboradorActual = $this->colaboradorModel->encontrarPorId($id);
            $foto_perfil = $colaboradorActual['foto_perfil'] ?? 'default-avatar.png';

            // Procesar nueva foto si se sube
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $tipoReal = mime_content_type($_FILES['foto_perfil']['tmp_name']);
                if (!in_array($tipoReal, $tiposPermitidos)) {
                    throw new ValidacionException('Solo se permiten imágenes JPG, PNG, GIF o WEBP', 'foto_perfil');
                }
                if ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
                    throw new ValidacionException('La imagen no puede superar 2MB', 'foto_perfil');
                }
                $extension = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
                $nombre = time() . '_' . uniqid() . '.' . $extension;
                $ruta = UPLOADS_PATH . '/avatars/' . $nombre;

                if (!is_dir(UPLOADS_PATH . '/avatars')) {
                    mkdir(UPLOADS_PATH . '/avatars', 0777, true);
                }

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta)) {
                    $foto_perfil = $nombre;
                }
            }

            $datos = [
                'primer_nombre' => $primer_nombre,
                'segundo_nombre' => $segundo_nombre,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'sexo' => $sexo,
                'identificacion' => $identificacion,
                'fecha_nacimiento' => $fecha_nacimiento,
                'email' => $email,
                'foto_perfil' => $foto_perfil
            ];

            if (isset($hash)) {
                $datos['password'] = $hash;
            }

            $this->colaboradorModel->actualizarColaborador($id, $datos);

            $_SESSION['success'] = 'Colaborador actualizado correctamente';
            $this->redirigir('/admin/colaboradores');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['campo_error'] = $e instanceof ValidacionException ? $e->campo : null;
            $viejo = $_POST;
            unset($viejo['password'], $viejo['csrf_token']);
            $_SESSION['old_input'] = $viejo;
            $this->redirigir('/admin/colaboradores/editar/' . $id);
        }
    }

    public function eliminar($id)
    {
        RolMiddleware::checkPermission('colaboradores', 'eliminar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');
            $this->colaboradorModel->eliminarColaborador($id);
            $_SESSION['success'] = 'Colaborador eliminado correctamente';

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirigir('/admin/colaboradores');
    }
}