<?php

namespace Controllers;

use Core\Controlador;
use Core\CSRF;
use Core\FirmaDigital;
use Helpers\Sanitizador;
use Helpers\Validador;
use Models\Ticket;
use Models\RespuestaTicket;
use Models\CategoriaTicket;
use Models\Colaborador;
use Models\Usuario;
use Middleware\AutenticacionMiddleware;
use Middleware\RolMiddleware;

class TicketController extends Controlador
{
    private $ticketModel;
    private $respuestaModel;
    private $categoriaModel;
    private $colaboradorModel;
    private $usuarioModel;

    public function __construct()
    {
        AutenticacionMiddleware::check();
        RolMiddleware::checkPermission('tickets', 'ver_todos');
        $this->ticketModel = new Ticket();
        $this->respuestaModel = new RespuestaTicket();
        $this->categoriaModel = new CategoriaTicket();
        $this->colaboradorModel = new Colaborador();
        $this->usuarioModel = new Usuario();
    }

    public function index()
    {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 5;
        
        $tickets = $this->ticketModel->obtenerPaginados($pagina, $porPagina);
        $total = $this->ticketModel->contarTodos();
        $totalPaginas = ceil($total / $porPagina);
        $categorias = $this->categoriaModel->obtenerTodos();
        
        $this->vista('admin/tickets/index', [
            'tickets' => $tickets,
            'categorias' => $categorias,
            'total' => $total,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina,
            'titulo' => 'Gestión de Tickets'
        ]);
    }

    public function crear()
    {
        $colaboradores = $this->colaboradorModel->obtenerTodos();
        $categorias = $this->categoriaModel->obtenerTodos();
        
        $this->vista('admin/tickets/crear', [
            'titulo' => 'Nuevo Ticket',
            'colaboradores' => $colaboradores,
            'categorias' => $categorias,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function guardar()
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $colaborador_id = (int)($_POST['colaborador_id'] ?? 0);
            $categoria_id   = (int)($_POST['categoria_id'] ?? 0);
            $titulo         = Sanitizador::sanitizarString($_POST['titulo'] ?? '');
            $descripcion    = Sanitizador::sanitizarString($_POST['descripcion'] ?? '');
            $prioridad      = $_POST['prioridad'] ?? 'Media';
            $nivel_soporte  = $_POST['nivel_soporte'] ?? 'Primer Nivel';

            Validador::validarRequerido([
                'colaborador_id' => $colaborador_id,
                'categoria_id'   => $categoria_id,
                'titulo'         => $titulo,
                'descripcion'    => $descripcion
            ]);

            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            $datos = [
                'colaborador_id' => $colaborador_id,
                'categoria_id'   => $categoria_id,
                'titulo'         => $titulo,
                'descripcion'    => $descripcion,
                'prioridad'      => $prioridad,
                'nivel_soporte'  => $nivel_soporte,
                'estado'         => 'En proceso',
                'ip_creacion'    => $ip
            ];

            $ticketId = $this->ticketModel->crearTicket($datos);

            $ticket = $this->ticketModel->obtenerPorId($ticketId);
            $firma  = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($ticketId, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Ticket creado correctamente';
            $this->redirigir('/admin/tickets');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/tickets/crear');
        }
    }

    public function ver($id)
    {
        $ticket = $this->ticketModel->obtenerPorId($id);
        if (!$ticket) {
            $_SESSION['error'] = 'Ticket no encontrado';
            $this->redirigir('/admin/tickets');
            return;
        }

        $firmaValida   = (new FirmaDigital())->verificarFirmaTicket($ticket, $ticket['firma_digital']);
        $respuestas    = $this->respuestaModel->obtenerPorTicket($id);
        $agentes       = $this->usuarioModel->obtenerTodos();
        $colaboradores = $this->colaboradorModel->obtenerTodos();

        $encuestaModel = new \Models\EncuestaSatisfaccion();
        $encuesta = $encuestaModel->obtenerPorTicket($id);

        $this->vista('admin/tickets/ver', [
            'ticket'       => $ticket,
            'respuestas'   => $respuestas,
            'agentes'      => $agentes,
            'colaboradores'=> $colaboradores,
            'firma_valida' => $firmaValida,
            'titulo'       => 'Detalle del Ticket #' . $id,
            'csrf_token'   => CSRF::generarToken(),
            'encuesta'     => $encuesta
        ]);
    }

       public function asignar()
    {
        RolMiddleware::checkPermission('tickets', 'asignar');
        $ticketId = (int)($_POST['ticket_id'] ?? 0);
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $agenteId = (int)($_POST['agente_id'] ?? 0);

            if ($agenteId <= 0) {
                throw new \Exception('No has asignado un agente. Selecciona uno antes de confirmar.');
            }

            $this->ticketModel->actualizarTicket($ticketId, ['agente_asignado_id' => $agenteId]);

            $ticket = $this->ticketModel->obtenerPorId($ticketId);
            $firma  = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($ticketId, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Ticket asignado correctamente';
            $this->redirigir('/admin/tickets/ver/' . $ticketId);

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/tickets/ver/' . $ticketId);
        }
    }

    public function responder()
    {
        RolMiddleware::checkPermission('tickets', 'responder');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $ticketId = (int)($_POST['ticket_id'] ?? 0);
            $mensaje  = Sanitizador::sanitizarString($_POST['mensaje'] ?? '');
            $agenteId = $_SESSION['user_id'];

            Validador::validarRequerido(['mensaje' => $mensaje]);

            $this->respuestaModel->crearRespuesta([
                'ticket_id' => $ticketId,
                'agente_id' => $agenteId,
                'mensaje'   => $mensaje
            ]);

            $respuestas = $this->respuestaModel->obtenerPorTicket($ticketId);
            if (count($respuestas) === 1) {
                $this->ticketModel->actualizarTicket($ticketId, [
                    'fecha_primer_respuesta' => date('Y-m-d H:i:s')
                ]);
            }

            $ticket = $this->ticketModel->obtenerPorId($ticketId);
            $firma  = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($ticketId, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Respuesta agregada correctamente';
            $this->redirigir('/admin/tickets/ver/' . $ticketId);

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/tickets');
        }
    }

    public function ponerEnEspera($id)
    {
        RolMiddleware::checkPermission('tickets', 'responder');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $this->ticketModel->actualizarTicket($id, ['estado' => 'En espera']);

            $ticket = $this->ticketModel->obtenerPorId($id);
            $firma  = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($id, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Ticket puesto en espera';
            $this->redirigir('/admin/tickets/ver/' . $id);

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/tickets');
        }
    }

    public function culminar($id)
    {
        RolMiddleware::checkPermission('tickets', 'culminar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $respuestas = $this->respuestaModel->obtenerPorTicket($id);
            if (empty($respuestas)) {
                throw new \Exception('No puedes culminar un ticket sin agregar al menos un comentario explicando la solución.');
            }

            $this->ticketModel->actualizarTicket($id, [
                'estado'           => 'Culminada',
                'fecha_culminacion' => date('Y-m-d H:i:s')
            ]);

            $ticket = $this->ticketModel->obtenerPorId($id);
            $firma  = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($id, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Ticket culminado correctamente';
            $this->redirigir('/admin/tickets');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/admin/tickets');
        }
    }

    public function eliminar($id)
    {
        RolMiddleware::checkPermission('tickets', 'eliminar');
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');
            $this->ticketModel->eliminarTicket($id);
            $_SESSION['success'] = 'Ticket eliminado correctamente';

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirigir('/admin/tickets');
    }
}