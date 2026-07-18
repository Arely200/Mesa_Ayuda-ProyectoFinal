<?php

namespace Controllers;

use Core\Controlador;
use Core\CSRF;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;
use Models\Permiso;

class RolController extends Controlador
{
    private $permisoModel;
    private $roles = ['admin', 'supervisor', 'agente'];
    private $modulos = [
        'dashboard' => ['ver'],
        'usuarios' => ['ver', 'crear', 'editar', 'eliminar'],
        'colaboradores' => ['ver', 'crear', 'editar', 'eliminar'],
        'tickets' => ['ver_todos', 'asignar', 'responder', 'culminar', 'eliminar'],
        'categorias' => ['ver', 'crear', 'editar', 'eliminar'],
        'reportes' => ['ver', 'exportar'],
        'logs' => ['ver'],
        'roles' => ['administrar']
    ];

    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('roles', 'administrar');
        $this->permisoModel = new Permiso();
    }

    public function index()
    {
        $this->vista('admin/roles/index', [
            'titulo' => 'Roles y Permisos',
            'roles' => $this->roles,
            'modulos' => $this->modulos,
            'permisos' => $this->permisoModel->obtenerMatriz(),
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function actualizar()
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');
            $seleccionados = $_POST['permisos'] ?? [];

            foreach ($this->roles as $rol) {
                foreach ($this->modulos as $modulo => $acciones) {
                    foreach ($acciones as $accion) {
                        $permitido = isset($seleccionados[$rol][$modulo][$accion]);
                        $this->permisoModel->guardarPermiso($rol, $modulo, $accion, $permitido);
                    }
                }
            }

            $_SESSION['success'] = 'Permisos actualizados correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirigir('/admin/roles');
    }
}
