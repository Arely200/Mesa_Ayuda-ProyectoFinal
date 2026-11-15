<?php

namespace Controllers;

use Core\Controlador;
use Core\CSRF;
use Helpers\Sanitizador;
use Helpers\Validador;
use Models\Colaborador;
use Models\Ticket;
use Models\CategoriaTicket;

class PublicController extends Controlador
{
    private $colaboradorModel;
    private $ticketModel;
    private $categoriaModel;

    public function __construct()
    {
        $this->colaboradorModel = new Colaborador();
        $this->ticketModel = new Ticket();
        $this->categoriaModel = new CategoriaTicket();
    }

    public function home()
    {
        $this->vista('marketing/index', [
            'titulo' => 'Mesa de Ayuda - Sistema de Gestión de Tickets'
        ]);
    }

    // NOTA: el login de colaboradores ahora se maneja en un único punto
    // de entrada, AuthController (/login), para que exista un solo
    // formulario de inicio de sesión para todo el sistema. Ver
    // AuthController::authenticate() / autenticarColaborador().

    public function dashboard()
    {
        $this->verificarSesion();

        $colaboradorId = $_SESSION['colaborador_id'];
        $tickets = $this->ticketModel->obtenerPorColaborador($colaboradorId);
        $totales = $this->ticketModel->contarPorColaborador($colaboradorId);

        $this->vista('public/dashboard', [
            'titulo' => 'Mi Dashboard',
            'tickets' => $tickets,
            'totales' => $totales,
            'nombre' => $_SESSION['colaborador_nombre']
        ]);
    }

    public function crearTicket()
    {
        $this->verificarSesion();

        $categorias = $this->categoriaModel->obtenerAgrupadasPorTipo();

        $this->vista('public/crear_ticket', [
            'titulo' => 'Nuevo Ticket',
            'categorias' => $categorias,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function storeTicket()
    {
        $this->verificarSesion();

        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $categoria_id = (int)($_POST['categoria_id'] ?? 0);
            $titulo = Sanitizador::sanitizarString($_POST['titulo'] ?? '');
            $descripcion = Sanitizador::sanitizarString($_POST['descripcion'] ?? '');
            $prioridad = $_POST['prioridad'] ?? 'Media';

            if (empty($categoria_id) || empty($titulo) || empty($descripcion)) {
                throw new \Exception('Todos los campos son obligatorios');
            }

            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            $datos = [
                'colaborador_id' => $_SESSION['colaborador_id'],
                'categoria_id' => $categoria_id,
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'prioridad' => $prioridad,
                'estado' => 'En proceso',
                'ip_creacion' => $ip
            ];

            $ticketId = $this->ticketModel->crearTicket($datos);

            $_SESSION['success'] = 'Ticket creado correctamente';
            $this->redirigir('/public/mis-tickets');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/public/crear-ticket');
        }
    }

    public function misTickets()
    {
        $this->verificarSesion();

        $tickets = $this->ticketModel->obtenerPorColaborador($_SESSION['colaborador_id']);

        $this->vista('public/mis_tickets', [
            'titulo' => 'Mis Tickets',
            'tickets' => $tickets
        ]);
    }

    public function verTicket($id)
{
    if (!isset($_SESSION['colaborador_id'])) {
        $_SESSION['error'] = 'Debes iniciar sesión';
        $this->redirigir('/login');
        return;
    }

    $ticket = $this->ticketModel->obtenerPorId($id);
    
    if (!$ticket) {
        $_SESSION['error'] = 'Ticket no encontrado';
        $this->redirigir('/public/mis-tickets');
        return;
    }

    if ($ticket['colaborador_id'] != $_SESSION['colaborador_id']) {
        $_SESSION['error'] = 'No tienes permiso para ver este ticket';
        $this->redirigir('/public/mis-tickets');
        return;
    }

    // Obtener respuestas del ticket
    $respuestaModel = new \Models\RespuestaTicket();
    $respuestas = $respuestaModel->obtenerPorTicket($id);

    // Verificar firma digital (si existe)
    $firma_valida = false;
    if (!empty($ticket['firma_digital'])) {
        $firmaDigital = new \Core\FirmaDigital();
        $firma_valida = $firmaDigital->verificarFirmaTicket($ticket, $ticket['firma_digital']);
    }

    $this->vista('public/ver_ticket', [
        'titulo' => 'Ticket #' . $id,
        'ticket' => $ticket,
        'respuestas' => $respuestas,
        'firma_valida' => $firma_valida
    ]);
}

    public function cambiarPassword()
    {
        $this->verificarSesion();

        $this->redirigir('/public/perfil');
    }

    public function perfil()
    {
        $this->verificarSesion();

        $colaborador = $this->colaboradorModel->encontrarPorId($_SESSION['colaborador_id']);
        if (!$colaborador) {
            $_SESSION['error'] = 'Perfil no encontrado';
            $this->redirigir('/public/dashboard');
            return;
        }

        $this->vista('public/perfil', [
            'titulo' => 'Mi Perfil',
            'colaborador' => $colaborador,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function actualizarFotoPerfil()
    {
        $this->verificarSesion();

        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            if (!isset($_FILES['foto_perfil']) || $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Debes seleccionar una imagen');
            }

            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $tipoReal = mime_content_type($_FILES['foto_perfil']['tmp_name']);
            if (!in_array($tipoReal, $tiposPermitidos, true)) {
                throw new \Exception('Solo se permiten imágenes JPG, PNG, GIF o WEBP');
            }

            if ($_FILES['foto_perfil']['size'] > 2 * 1024 * 1024) {
                throw new \Exception('La imagen no puede superar 2MB');
            }

            $extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $extensionesPermitidas, true)) {
                throw new \Exception('La extensión de la imagen no es válida');
            }

            if (!is_dir(UPLOADS_PATH . '/avatars')) {
                mkdir(UPLOADS_PATH . '/avatars', 0777, true);
            }

            $nombre = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $ruta = UPLOADS_PATH . '/avatars/' . $nombre;

            if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta)) {
                throw new \Exception('No se pudo guardar la imagen');
            }

            $this->colaboradorModel->actualizarColaborador($_SESSION['colaborador_id'], [
                'foto_perfil' => $nombre
            ]);

            $_SESSION['colaborador_foto'] = $nombre;
            $_SESSION['success'] = 'Foto de perfil actualizada correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirigir('/public/perfil');
    }

    public function updatePassword()
    {
        $this->verificarSesion();

        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $password_actual = $_POST['password_actual'] ?? '';
            $password_nueva = $_POST['password_nueva'] ?? '';
            $password_confirmar = $_POST['password_confirmar'] ?? '';

            if (empty($password_actual) || empty($password_nueva) || empty($password_confirmar)) {
                throw new \Exception('Todos los campos son obligatorios');
            }

            if ($password_nueva !== $password_confirmar) {
                throw new \Exception('Las contraseñas nuevas no coinciden');
            }

            Validador::validarPassword($password_nueva);

            $colaborador = $this->colaboradorModel->encontrarPorId($_SESSION['colaborador_id']);

            $hasher = new \Core\HashPassword();

            if (!$hasher->verificar($password_actual, $colaborador['password'])) {
                throw new \Exception('Contraseña actual incorrecta');
            }

            $hash = $hasher->generar($password_nueva);
            $this->colaboradorModel->actualizar($colaborador['id'], ['password' => $hash]);

            $_SESSION['success'] = 'Contraseña actualizada correctamente';
            $this->redirigir('/public/dashboard');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/public/perfil');
        }
    }

    private function verificarSesion()
    {
        if (!isset($_SESSION['colaborador_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            $this->redirigir('/login');
            exit;
        }
    }

    public function mostrarEncuesta($id)
    {
        if (!isset($_SESSION['colaborador_id'])) {
            $_SESSION['error'] = 'Debes iniciar sesión';
            $this->redirigir('/login');
            return;
        }

        $ticket = $this->ticketModel->obtenerPorId($id);
        if (!$ticket) {
            $_SESSION['error'] = 'Ticket no encontrado';
            $this->redirigir('/public/mis-tickets');
            return;
        }

        if ($ticket['colaborador_id'] != $_SESSION['colaborador_id']) {
            $_SESSION['error'] = 'No tienes permiso';
            $this->redirigir('/public/mis-tickets');
            return;
        }

        if ($ticket['estado'] !== 'Culminada') {
            $_SESSION['error'] = 'Este ticket aún no está culminado';
            $this->redirigir('/public/mis-tickets');
            return;
        }

        $encuestaModel = new \Models\EncuestaSatisfaccion();
        $encuesta = $encuestaModel->obtenerPorTicket($id);

        if ($encuesta) {
            $_SESSION['success'] = 'Ya has respondido la encuesta';
            $this->redirigir('/public/mis-tickets');
            return;
        }

        $this->vista('public/encuesta', [
            'titulo' => 'Encuesta de Satisfacción',
            'ticket' => $ticket,
            'csrf_token' => CSRF::generarToken()
        ]);
    }

    public function guardarEncuesta()
    {
        try {
            CSRF::validarToken($_POST['csrf_token'] ?? '');

            $ticketId = (int)($_POST['ticket_id'] ?? 0);
            $calificacion = $_POST['calificacion'] ?? '';
            $comentario = Sanitizador::sanitizarString($_POST['comentario'] ?? '');

            if (empty($calificacion)) {
                throw new \Exception('Debes seleccionar una calificación');
            }

            $datos = [
                'ticket_id' => $ticketId,
                'calificacion' => $calificacion,
                'comentario' => $comentario
            ];

            $encuestaModel = new \Models\EncuestaSatisfaccion();
            $encuestaModel->crearEncuesta($datos);

            $_SESSION['success'] = '¡Gracias por tu encuesta!';
            $this->redirigir('/public/mis-tickets');

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirigir('/public/encuesta/' . ($_POST['ticket_id'] ?? 0));
        }
    }

    public function noticias()
    {
        $this->vista('marketing/noticias', [
            'titulo' => 'Noticias - Mesa de Ayuda'
        ]);
    }

    public function contacto()
    {
        $this->vista('marketing/contacto', [
            'titulo' => 'Contacto - Mesa de Ayuda'
        ]);
    }

    public function about()
    {
        $this->vista('marketing/about', [
            'titulo' => 'Sobre Nosotros - Mesa de Ayuda'
        ]);
    }
}
