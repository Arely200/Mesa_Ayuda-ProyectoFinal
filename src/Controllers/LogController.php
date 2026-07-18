<?php

namespace Controllers;

use Core\Controlador;
use Models\LogLogin;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class LogController extends Controlador
{
    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('logs', 'ver');
    }

    public function index()
    {
        $logModel = new LogLogin();
        $logs = $logModel->obtenerTodos();

        $this->vista('admin/logs_login', [
            'titulo' => 'Logs de Login',
            'logs' => $logs
        ]);
    }
}
