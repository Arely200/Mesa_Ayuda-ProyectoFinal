<?php

namespace Controllers;

use Core\Controlador;
use Core\CSRF;
use Helpers\Sanitizador;
use Helpers\Validador;
use Models\CategoriaTicket;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class CategoriaController extends Controlador
{
    private $categoriaModel;

    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('categorias', 'ver');
        $this->categoriaModel = new CategoriaTicket();
    }

    public function index()
    {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 5;

        $categorias = $this->categoriaModel->obtenerPaginados($pagina, $porPagina);
        $total = $this->categoriaModel->contarTodos();
        $totalPaginas = ceil($total / $porPagina);

        $this->vista('admin/categorias/index', [
            'titulo' => 'Gestión de Categorías',
            'categorias' => $categorias,
            'total' => $total,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function crear()
    {
        RolMiddleware::checkPermission('categorias', 'crear');
        $this->vista('admin/categorias/crear', [
            'titulo' => 'Nueva Categoría',
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function guardar()
    {
        RolMiddleware::checkPermission('categorias', 'crear');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $nombre = Sanitizador::sanitizarString($_POST['nombre'] ?? '');
            $tipo = $_POST['tipo'] ?? 'Soporte';
            $descripcion = Sanitizador::sanitizarString($_POST['descripcion'] ?? '');

            Validador::validarRequerido(['nombre' => $nombre]);
            if (!in_array($tipo, ['Soporte', 'Académico'], true)) {
                throw new \Exception('El tipo de categoría no es válido');
            }

            // Verificar si ya existe
            $existe = $this->categoriaModel->obtenerPorNombre($nombre);
            if ($existe) {
                $_SESSION['error'] = 'Ya existe una categoría con ese nombre';
                $this->redirigir('/admin/categorias/crear');
                return;
            }

            $datos = [
                'nombre' => $nombre,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
                'activo' => 1
            ];

            $this->categoriaModel->crearCategoria($datos);

            $_SESSION['success'] = 'Categoría creada correctamente';
            $this->redirigir('/admin/categorias');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/categorias/crear');
        }
    }

    public function editar($id)
    {
        RolMiddleware::checkPermission('categorias', 'editar');
        $categoria = $this->categoriaModel->encontrarPorId($id);
        if (!$categoria) {
            $_SESSION['error'] = 'Categoría no encontrada';
            $this->redirigir('/admin/categorias');
            return;
        }

        $this->vista('admin/categorias/editar', [
            'titulo' => 'Editar Categoría',
            'categoria' => $categoria,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function actualizar($id)
    {
        RolMiddleware::checkPermission('categorias', 'editar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $nombre = Sanitizador::sanitizarString($_POST['nombre'] ?? '');
            $tipo = $_POST['tipo'] ?? 'Soporte';
            $descripcion = Sanitizador::sanitizarString($_POST['descripcion'] ?? '');
            $activo = (int)($_POST['activo'] ?? 0) === 1 ? 1 : 0;

            Validador::validarRequerido(['nombre' => $nombre]);
            if (!in_array($tipo, ['Soporte', 'Académico'], true)) {
                throw new \Exception('El tipo de categoría no es válido');
            }

            $datos = [
                'nombre' => $nombre,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
                'activo' => $activo
            ];

            $this->categoriaModel->actualizarCategoria($id, $datos);

            $_SESSION['success'] = 'Categoría actualizada correctamente';
            $this->redirigir('/admin/categorias');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/categorias/editar/' . $id);
        }
    }

    public function eliminar($id)
    {
        RolMiddleware::checkPermission('categorias', 'eliminar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');
            $this->categoriaModel->eliminarCategoria($id);
            $_SESSION['success'] = 'Categoría eliminada correctamente';

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirigir('/admin/categorias');
    }
}
