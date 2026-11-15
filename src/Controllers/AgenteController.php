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
use Middleware\AutenticacionMiddleware;

class AgenteController extends Controlador
{
    private $ticketModel;
    private $respuestaModel;
    private $categoriaModel;

    public function __construct()
    {
        AutenticacionMiddleware::check();
        
        $rol = strtolower($_SESSION['user_rol'] ?? '');
        if ($rol !== 'agente' && $rol !== 'supervisor') {
            $this->redirigir('/admin/dashboard');
            return;
        }
        
        $this->ticketModel = new Ticket();
        $this->respuestaModel = new RespuestaTicket();
        $this->categoriaModel = new CategoriaTicket();
    }

    public function dashboard()
    {
        $agenteId = $_SESSION['user_id'];
        $tickets = $this->ticketModel->obtenerPorAgente($agenteId);
        
        $totales = [
            'total' => count($tickets),
            'proceso' => count(array_filter($tickets, fn($t) => $t['estado'] === 'En proceso')),
            'espera' => count(array_filter($tickets, fn($t) => $t['estado'] === 'En espera')),
            'culminada' => count(array_filter($tickets, fn($t) => $t['estado'] === 'Culminada')),
        ];

        $this->vista('agente/dashboard', [
            'titulo' => 'Panel del Agente',
            'tickets' => $tickets,
            'totales' => $totales,
            'nombre' => $_SESSION['user_nombre'] ?? 'Agente'
        ]);
    }

    public function misTickets()
    {
        $agenteId = $_SESSION['user_id'];
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 5;

        $tickets = $this->ticketModel->obtenerPorAgentePaginado($agenteId, $pagina, $porPagina);
        $total = $this->ticketModel->contarPorAgente($agenteId);
        $totalPaginas = ceil($total / $porPagina);

        $this->vista('agente/tickets', [
            'titulo' => 'Mis Tickets',
            'tickets' => $tickets,
            'total' => $total,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'porPagina' => $porPagina
        ]);
    }

    public function verTicket($id)
    {
        $agenteId = $_SESSION['user_id'];
        $ticket = $this->ticketModel->obtenerPorId($id);

        if (!$ticket) {
            $_SESSION['error'] = 'Ticket no encontrado';
            $this->redirigir('/agente/tickets');
            return;
        }

        if ($ticket['agente_asignado_id'] != $agenteId) {
            $_SESSION['error'] = 'No tienes permiso para ver este ticket';
            $this->redirigir('/agente/tickets');
            return;
        }

        $firmaValida = (new FirmaDigital())->verificarFirmaTicket($ticket, $ticket['firma_digital']);
        $respuestas = $this->respuestaModel->obtenerPorTicket($id);
        
        $encuestaModel = new \Models\EncuestaSatisfaccion();
        $encuesta = $encuestaModel->obtenerPorTicket($id);

        $this->vista('agente/ver_ticket', [
            'titulo' => 'Ticket #' . $id,
            'ticket' => $ticket,
            'respuestas' => $respuestas,
            'firma_valida' => $firmaValida,
            'csrf_token' => CSRF::generarToken(),
            'encuesta' => $encuesta
        ]);
    }

    public function responder()
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $ticketId = (int)($_POST['ticket_id'] ?? 0);
            $mensaje = Sanitizador::sanitizarString($_POST['mensaje'] ?? '');
            $agenteId = $_SESSION['user_id'];

            Validador::validarRequerido(['mensaje' => $mensaje]);

            $this->respuestaModel->crearRespuesta([
                'ticket_id' => $ticketId,
                'agente_id' => $agenteId,
                'mensaje' => $mensaje
            ]);

            $respuestas = $this->respuestaModel->obtenerPorTicket($ticketId);
            if (count($respuestas) === 1) {
                $this->ticketModel->actualizarTicket($ticketId, [
                    'fecha_primer_respuesta' => date('Y-m-d H:i:s')
                ]);
            }

            $ticket = $this->ticketModel->obtenerPorId($ticketId);
            $firma = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($ticketId, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Respuesta agregada correctamente';
            $this->redirigir('/agente/ticket/' . $ticketId);

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/agente/tickets');
        }
    }

    public function ponerEnEspera($id)
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $this->ticketModel->actualizarTicket($id, ['estado' => 'En espera']);

            $ticket = $this->ticketModel->obtenerPorId($id);
            $firma = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($id, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Ticket puesto en espera';
            $this->redirigir('/agente/ticket/' . $id);

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/agente/tickets');
        }
    }

    public function culminar($id)
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $respuestas = $this->respuestaModel->obtenerPorTicket($id);
            if (empty($respuestas)) {
                throw new \Exception('No puedes culminar un ticket sin agregar al menos un comentario explicando la solución.');
            }

            $solucion = Sanitizador::sanitizarString($_POST['solucion'] ?? '');
            if (empty(trim($solucion))) {
                throw new \Exception('Debes escribir cómo resolviste el problema antes de culminar el ticket.');
            }

            $this->ticketModel->actualizarTicket($id, [
                'estado' => 'Culminada',
                'solucion' => $solucion,
                'fecha_culminacion' => date('Y-m-d H:i:s')
            ]);

            $ticket = $this->ticketModel->obtenerPorId($id);
            $firma = (new FirmaDigital())->generarFirmaTicket($ticket);
            $this->ticketModel->actualizarTicket($id, ['firma_digital' => $firma]);

            $_SESSION['success'] = 'Ticket culminado correctamente';
            $this->redirigir('/agente/tickets');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/agente/ticket/' . $id);
        }
    }
}