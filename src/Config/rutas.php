<?php

use Core\Enrutador;

if (!isset($enrutador) || !$enrutador instanceof Enrutador) {
    $enrutador = new Enrutador();
}

// ============================================================
// RUTAS DE AUTENTICACIÓN
// ============================================================
$enrutador->agregar('GET',  '/login',  'AuthController', 'showLogin');
$enrutador->agregar('POST', '/login',  'AuthController', 'authenticate');
$enrutador->agregar('GET',  '/logout', 'AuthController', 'logout');
$enrutador->agregar('POST', '/logout', 'AuthController', 'logout');

// ============================================================
// RUTAS DEL DASHBOARD
// ============================================================
$enrutador->agregar('GET', '/admin/dashboard', 'DashboardController', 'index');

// ============================================================
// RUTAS DEL PANEL SUPERVISOR
// ============================================================
$enrutador->agregar('GET', '/supervisor/dashboard', 'SupervisorController', 'dashboard');

// ============================================================
// RUTAS DE USUARIOS
// ============================================================
$enrutador->agregar('GET',  '/admin/usuarios',              'UserController', 'index');
$enrutador->agregar('GET',  '/admin/usuarios/crear',        'UserController', 'crear');
$enrutador->agregar('POST', '/admin/usuarios/guardar',      'UserController', 'guardar');
$enrutador->agregar('GET',  '/admin/usuarios/editar/{id}',  'UserController', 'editar');
$enrutador->agregar('POST', '/admin/usuarios/actualizar/{id}', 'UserController', 'actualizar');
$enrutador->agregar('POST', '/admin/usuarios/eliminar/{id}',   'UserController', 'eliminar');

// ============================================================
// RUTAS DE COLABORADORES
// ============================================================
$enrutador->agregar('GET',  '/admin/colaboradores',                 'ColaboradorController', 'index');
$enrutador->agregar('GET',  '/admin/colaboradores/crear',           'ColaboradorController', 'crear');
$enrutador->agregar('POST', '/admin/colaboradores/guardar',         'ColaboradorController', 'guardar');
$enrutador->agregar('GET',  '/admin/colaboradores/editar/{id}',     'ColaboradorController', 'editar');
$enrutador->agregar('POST', '/admin/colaboradores/actualizar/{id}', 'ColaboradorController', 'actualizar');
$enrutador->agregar('POST', '/admin/colaboradores/eliminar/{id}',   'ColaboradorController', 'eliminar');

// ============================================================
// RUTAS DE TICKETS
// ============================================================
$enrutador->agregar('GET',  '/admin/tickets',                  'TicketController', 'index');
$enrutador->agregar('GET',  '/admin/tickets/crear',            'TicketController', 'crear');
$enrutador->agregar('POST', '/admin/tickets/guardar',          'TicketController', 'guardar');
$enrutador->agregar('GET',  '/admin/tickets/ver/{id}',         'TicketController', 'ver');
$enrutador->agregar('POST', '/admin/tickets/asignar',          'TicketController', 'asignar');
$enrutador->agregar('POST', '/admin/tickets/responder',        'TicketController', 'responder');
$enrutador->agregar('POST', '/admin/tickets/culminar/{id}',    'TicketController', 'culminar');
$enrutador->agregar('POST', '/admin/tickets/eliminar/{id}',    'TicketController', 'eliminar');
$enrutador->agregar('POST', '/admin/tickets/en-espera/{id}',   'TicketController', 'ponerEnEspera');

// ============================================================
// RUTAS DE REPORTES
// ============================================================
$enrutador->agregar('GET', '/admin/reportes',                    'ReporteController', 'index');
$enrutador->agregar('GET', '/admin/reportes/culminados',         'ReporteController', 'culminados');
$enrutador->agregar('GET', '/admin/reportes/categorias',         'ReporteController', 'estadisticasCategoria');
$enrutador->agregar('GET', '/admin/reportes/categorias/excel',   'ReporteController', 'exportarEstadisticasCategoriaExcel');
$enrutador->agregar('GET', '/admin/reportes/estadisticas','ReporteController', 'estadisticas');
$enrutador->agregar('GET', '/admin/reportes/excel',       'ReporteController', 'exportarExcel');

// ============================================================
// RUTAS DE CATEGORÍAS
// ============================================================
$enrutador->agregar('GET',  '/admin/categorias',                 'CategoriaController', 'index');
$enrutador->agregar('GET',  '/admin/categorias/crear',           'CategoriaController', 'crear');
$enrutador->agregar('POST', '/admin/categorias/guardar',         'CategoriaController', 'guardar');
$enrutador->agregar('GET',  '/admin/categorias/editar/{id}',     'CategoriaController', 'editar');
$enrutador->agregar('POST', '/admin/categorias/actualizar/{id}', 'CategoriaController', 'actualizar');
$enrutador->agregar('POST', '/admin/categorias/eliminar/{id}',   'CategoriaController', 'eliminar');

// ============================================================
// RUTAS DE LOGS
// ============================================================
$enrutador->agregar('GET', '/admin/logs', 'LogController', 'index');

// ============================================================
// RUTAS DE ROLES Y PERMISOS
// ============================================================
$enrutador->agregar('GET',  '/admin/roles',            'RolController', 'index');
$enrutador->agregar('POST', '/admin/roles/actualizar', 'RolController', 'actualizar');

// ============================================================
// PÁGINA DE INICIO (MARKETING)
// ============================================================
$enrutador->agregar('GET', '/', 'PublicController', 'home');

// ============================================================
// RUTAS DE MARKETING
// ============================================================
$enrutador->agregar('GET', '/noticias', 'PublicController', 'noticias');
$enrutador->agregar('GET', '/contacto', 'PublicController', 'contacto');
$enrutador->agregar('GET', '/about',    'PublicController', 'about');

// ============================================================
// RUTAS DEL PORTAL PÚBLICO (COLABORADORES)
// ============================================================
// Se mantienen estas rutas por compatibilidad con enlaces antiguos,
// pero ahora apuntan al mismo (único) controlador de login del sistema.
$enrutador->agregar('GET',  '/public/login',            'AuthController', 'showLogin');
$enrutador->agregar('POST', '/public/login',            'AuthController', 'authenticate');
$enrutador->agregar('POST', '/public/logout',           'AuthController', 'logout');
$enrutador->agregar('GET',  '/public/dashboard',        'PublicController', 'dashboard');
$enrutador->agregar('GET',  '/public/crear-ticket',     'PublicController', 'crearTicket');
$enrutador->agregar('POST', '/public/crear-ticket',     'PublicController', 'storeTicket');
$enrutador->agregar('GET',  '/public/mis-tickets',      'PublicController', 'misTickets');
$enrutador->agregar('GET',  '/public/ver-ticket/{id}',  'PublicController', 'verTicket');
$enrutador->agregar('GET',  '/public/perfil',           'PublicController', 'perfil');
$enrutador->agregar('POST', '/public/perfil/foto',      'PublicController', 'actualizarFotoPerfil');
$enrutador->agregar('GET',  '/public/cambiar-password', 'PublicController', 'cambiarPassword');
$enrutador->agregar('POST', '/public/cambiar-password', 'PublicController', 'updatePassword');
$enrutador->agregar('GET',  '/public/encuesta/{id}',    'PublicController', 'mostrarEncuesta');
$enrutador->agregar('POST', '/public/guardar-encuesta', 'PublicController', 'guardarEncuesta');

// ============================================================
// RUTAS DEL PANEL AGENTE
// ============================================================
$enrutador->agregar('GET',  '/agente/dashboard',          'AgenteController', 'dashboard');
$enrutador->agregar('GET',  '/agente/tickets',            'AgenteController', 'misTickets');
$enrutador->agregar('GET',  '/agente/ticket/{id}',        'AgenteController', 'verTicket');
$enrutador->agregar('POST', '/agente/responder',          'AgenteController', 'responder');
$enrutador->agregar('POST', '/agente/culminar/{id}',      'AgenteController', 'culminar');
$enrutador->agregar('POST', '/agente/en-espera/{id}',     'AgenteController', 'ponerEnEspera');
