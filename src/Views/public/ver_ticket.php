<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Detalle Ticket' ?> - Mesa de Ayuda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; }
        .header { background: #0D47A1; color: #fff; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .nav-menu { background: #fff; padding: 0 32px; display: flex; gap: 8px; border-bottom: 1px solid #E0E0E0; }
        .nav-menu a { padding: 16px 20px; color: #616161; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 3px solid transparent; }
        .nav-menu a:hover, .nav-menu a.active { color: #0D47A1; border-bottom-color: #1E88E5; }
        .content { padding: 32px; max-width: 900px; margin: 0 auto; }
        .card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .card h2 { margin-bottom: 16px; color: #0D47A1; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .campo { margin-bottom: 12px; }
        .campo label { font-weight: 600; color: #616161; font-size: 13px; display: block; }
        .campo .valor { font-size: 15px; color: #212121; padding: 6px 0; }
        .badge { padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 500; display: inline-block; }
        .badge-proceso { background: #E3F2FD; color: #0D47A1; }
        .badge-espera { background: #FFF8E1; color: #F57F17; }
        .badge-culminada { background: #E8F5E9; color: #2E7D32; }
        .badge-alta { background: #FFEBEE; color: #C62828; }
        .badge-media { background: #FFF3E0; color: #E65100; }
        .badge-baja { background: #E8F5E9; color: #2E7D32; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1E88E5; color: #fff; }
        .btn-primary:hover { background: #1565C0; }
        .btn-secondary { background: #E0E0E0; color: #616161; }
        .btn-secondary:hover { background: #BDBDBD; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .flex { display: flex; gap: 12px; flex-wrap: wrap; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #C8E6C9; }
        .alert-error { background: #FFEBEE; color: #C62828; border: 1px solid #FFCDD2; }
        .btn-encuesta { display: inline-block; padding: 12px 32px; background: #1E88E5; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .btn-encuesta:hover { background: #1565C0; }
        .card-encuesta { background: #E3F2FD; border: 2px solid #1E88E5; padding: 24px; border-radius: 12px; text-align: center; margin-bottom: 24px; }
        .card-encuesta h2 { color: #0D47A1; margin-bottom: 8px; }
        .card-encuesta p { color: #616161; margin-bottom: 16px; }
        .card-gracias { background: #E8F5E9; border: 2px solid #43A047; padding: 24px; border-radius: 12px; text-align: center; margin-bottom: 24px; }
        .card-gracias h2 { color: #2E7D32; margin-bottom: 8px; }
        .card-gracias p { color: #616161; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
    <div class="logo"></i> Portal Colaborador</div>
    <div class="header-usuario">
        <span class="avatar-circulo">
            <?php if (!empty($_SESSION['colaborador_foto']) && $_SESSION['colaborador_foto'] !== 'default-avatar.png'): ?>
                <img src="/Mesa_Ayuda/public/uploads/avatars/<?= htmlspecialchars($_SESSION['colaborador_foto']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <?= htmlspecialchars($_SESSION['colaborador_inicial'] ?? 'U') ?>
            <?php endif; ?>
        </span>
        <span class="nombre"><?= htmlspecialchars($_SESSION['colaborador_nombre'] ?? 'Usuario') ?></span>
        <form method="POST" action="/Mesa_Ayuda/public/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" style="background:rgba(255,255,255,0.2);color:#fff;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;">Cerrar</button>
        </form>
    </div>
</header>

<nav class="nav-menu">
    <a href="/Mesa_Ayuda/public/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/public/crear-ticket"><i class="bi bi-plus-circle"></i> Nuevo Ticket</a>
    <a href="/Mesa_Ayuda/public/mis-tickets" class="active"><i class="bi bi-clipboard-check"></i> Mis Tickets</a>
    <a href="/Mesa_Ayuda/public/perfil"><i class="bi bi-person-circle"></i> Mi Perfil</a>
</nav>

<div class="content">

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ======================================== -->
    <!-- TÍTULO Y VOLVER -->
    <!-- ======================================== -->
    <div class="header-actions">
        <h1><?= $titulo ?></h1>
        <a href="/Mesa_Ayuda/public/mis-tickets" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>

    <!-- ======================================== -->
    <!-- SOLUCIÓN -->
    <!-- ======================================== -->
    <?php if (!empty($ticket['solucion'])): ?>
        <div class="card">
            <h2><i class="bi bi-check2-circle"></i> Solución</h2>
            <div style="border-left:3px solid #43A047;padding:12px 16px;background:#f1f8f2;border-radius:0 6px 6px 0;">
                <?= nl2br(htmlspecialchars($ticket['solucion'])) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- ======================================== -->
    <!-- INFORMACIÓN DEL TICKET -->
    <!-- ======================================== -->
    <div class="card">
        <h2><i class="bi bi-clipboard-check"></i> Información del Ticket</h2>
        <div class="grid-2">
            <div>
                <div class="campo">
                    <label>Título</label>
                    <div class="valor"><strong><?= htmlspecialchars($ticket['titulo']) ?></strong></div>
                </div>
                <div class="campo">
                    <label>Descripción</label>
                    <div class="valor"><?= nl2br(htmlspecialchars($ticket['descripcion'])) ?></div>
                </div>
                <div class="campo">
                    <label>Categoría</label>
                    <div class="valor"><?= htmlspecialchars($ticket['categoria_nombre'] ?? 'N/A') ?></div>
                </div>
            </div>
            <div>
                <div class="campo">
                    <label>Estado</label>
                    <div class="valor"><span class="badge badge-<?= strtolower($ticket['estado']) ?>"><?= $ticket['estado'] ?></span></div>
                </div>
                <div class="campo">
                    <label>Prioridad</label>
                    <div class="valor"><span class="badge badge-<?= strtolower($ticket['prioridad']) ?>"><?= $ticket['prioridad'] ?></span></div>
                </div>
                <div class="campo">
                    <label>Agente Asignado</label>
                    <div class="valor"><?= htmlspecialchars($ticket['agente_nombre'] ?? 'Sin asignar') ?></div>
                </div>
                <div class="campo">
                    <label>Fecha de Creación</label>
                    <div class="valor"><?= date('d/m/Y H:i:s', strtotime($ticket['fecha_creacion'])) ?></div>
                </div>
                <?php if ($ticket['fecha_culminacion']): ?>
                    <div class="campo">
                        <label>Fecha de Culminación</label>
                        <div class="valor"><?= date('d/m/Y H:i:s', strtotime($ticket['fecha_culminacion'])) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- ENCUESTA DE SATISFACCIÓN -->
    <!-- ======================================== -->
    <?php if ($ticket['estado'] === 'Culminada'): 
        // Verificar directamente en la base de datos si ya tiene encuesta
        $db = \Config\BaseDatos::obtenerInstancia()->obtenerConexion();
        $stmt = $db->prepare("SELECT * FROM encuestas_satisfaccion WHERE ticket_id = ?");
        $stmt->execute([$ticket['id']]);
        $encuesta = $stmt->fetch();
    ?>

        <?php if (!$encuesta): ?>
            <div class="card-encuesta">
                <h2><i class="bi bi-pencil-square"></i> ¡Cuéntanos tu experiencia!</h2>
                <p>Ayúdanos a mejorar calificando la atención que recibiste</p>
                <a href="/Mesa_Ayuda/public/encuesta/<?= $ticket['id'] ?>" class="btn-encuesta">
                    <i class="bi bi-pencil-square"></i> Llenar Encuesta
                </a>
            </div>
        <?php else: ?>
            <div class="card-gracias">
                <h2><i class="bi bi-check-circle"></i> ¡Gracias por tu respuesta!</h2>
                <p>Ya has completado la encuesta de satisfacción para este ticket.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>
</body>
</html>
