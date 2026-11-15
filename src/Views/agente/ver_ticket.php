<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> - Mesa de Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>

<header class="header">
    <div style="font-size:20px;font-weight:700;">Panel del Agente</div>
    <div>
        <span><?= $_SESSION['user_nombre'] ?? '' ?></span>
        <form method="POST" action="/Mesa_Ayuda/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
        </form>
    </div>
</header>


<nav class="nav-menu">
    <a href="/Mesa_Ayuda/agente/dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/agente/tickets" class="active"><i class="bi bi-ticket-perforated"></i> Mis Tickets</a>
</nav>


<div class="content">

    <!-- ALERTAS -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- HEADER DE ACCIONES -->
    <div class="header-actions">
        <h1><?= htmlspecialchars($titulo) ?></h1>
        <div class="flex">
            <a href="/Mesa_Ayuda/agente/tickets" class="btn btn-secondary">← Volver</a>
            <?php if ($ticket['estado'] !== 'Culminada'): ?>
                <form method="POST" action="/Mesa_Ayuda/agente/en-espera/<?= $ticket['id'] ?>" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('¿Poner en espera?')">En Espera</button>
                </form>
                <?php if (!empty($respuestas)): ?>
                    <button type="button" class="btn btn-success" onclick="document.getElementById('modal-solucion').style.display='flex'">Culminar</button>
                <?php else: ?>
                    <button type="button" class="btn btn-success" disabled title="Agrega un comentario antes de culminar" style="opacity:0.5;cursor:not-allowed;">Culminar</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- FIRMA -->
    <div class="card">
        <h2>Integridad del Ticket</h2>
        <span class="firma <?= $firma_valida ? 'firma-valida' : 'firma-invalida' ?>">
            <?= $firma_valida ? '✓ Firma válida' : '✗ Firma inválida' ?>
        </span>
    </div>

    <!-- INFO TICKET -->
    <div class="card">
        <h2>Información del Ticket</h2>
        <div class="grid-2">
            <div>
                <div class="campo"><label>Título</label><div class="valor"><strong><?= htmlspecialchars($ticket['titulo']) ?></strong></div></div>
                <div class="campo"><label>Descripción</label><div class="valor"><?= nl2br(htmlspecialchars($ticket['descripcion'])) ?></div></div>
                <div class="campo"><label>Categoría</label><div class="valor"><?= htmlspecialchars($ticket['categoria_nombre'] ?? 'N/A') ?></div></div>
            </div>
            <div>
                <div class="campo"><label>Estado</label><div class="valor"><span class="badge badge-<?= $ticket['estado'] ?>"><?= $ticket['estado'] ?></span></div></div>
                <div class="campo"><label>Prioridad</label><div class="valor"><span class="badge badge-<?= $ticket['prioridad'] ?>"><?= $ticket['prioridad'] ?></span></div></div>
                <div class="campo"><label>Colaborador</label><div class="valor"><?= htmlspecialchars($ticket['colaborador_nombre'] ?? 'N/A') ?></div></div>
                <div class="campo"><label>Fecha de Creación</label><div class="valor"><?= date('d/m/Y H:i:s', strtotime($ticket['fecha_creacion'])) ?></div></div>
                <?php if ($ticket['fecha_culminacion']): ?>
                    <div class="campo">
                        <label>Tiempo de Tramitación</label>
                        <div class="valor">
                            <?php
                            $inicio = new DateTime($ticket['fecha_creacion']);
                            $fin    = new DateTime($ticket['fecha_culminacion']);
                            $diff   = $inicio->diff($fin);
                            echo $diff->format('%d días, %h horas, %i minutos');
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SOLUCIÓN (Solo Agente/Admin) -->
    <?php if (!empty($ticket['solucion'])): ?>
        <div class="card">
            <h2>Solución</h2>
            <div class="solucion-box">
                <?= nl2br(htmlspecialchars($ticket['solucion'])) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- EVALUACIÓN DEL COLABORADOR (solo lectura) -->
    <div class="card">
        <h2>Evaluación del Colaborador</h2>
        <?php if (!empty($encuesta)): ?>
            <?php
                $colores = [
                    'Satisfecho'    => ['bg' => '#E8F5E9', 'txt' => '#2E7D32'],
                    'Conforme'      => ['bg' => '#E3F2FD', 'txt' => '#0D47A1'],
                    'Inconforme'    => ['bg' => '#FFEBEE', 'txt' => '#C62828'],
                    'No respondida' => ['bg' => '#F5F5F5', 'txt' => '#757575'],
                ];
                $c = $colores[$encuesta['calificacion']] ?? $colores['No respondida'];
            ?>
            <span class="badge" style="background:<?= $c['bg'] ?>;color:<?= $c['txt'] ?>;">
                <?= htmlspecialchars($encuesta['calificacion']) ?>
            </span>
            <?php if (!empty($encuesta['comentario'])): ?>
                <div class="valor" style="margin-top:10px;">
                    <?= nl2br(htmlspecialchars($encuesta['comentario'])) ?>
                </div>
            <?php endif; ?>
            <div style="font-size:12px;color:#999;margin-top:8px;">
                Respondida el <?= date('d/m/Y H:i', strtotime($encuesta['fecha'])) ?>
            </div>
        <?php else: ?>
            <p style="color:#999;">El colaborador aún no ha respondido la encuesta de satisfacción.</p>
        <?php endif; ?>
    </div>

    <!-- HISTORIAL -->
    <div class="card">
        <h2>Historial de Respuestas</h2>
        <?php if (empty($respuestas)): ?>
            <p style="color:#999;">No hay respuestas aún.</p>
        <?php else: ?>
            <?php foreach ($respuestas as $r): ?>
                <div class="respuesta">
                    <span class="agente"><?= htmlspecialchars($r['agente_nombre'] ?? 'Agente') ?></span>
                    <span class="fecha"> — <?= date('d/m/Y H:i:s', strtotime($r['fecha_respuesta'])) ?></span>
                    <div class="mensaje"><?= nl2br(htmlspecialchars($r['mensaje'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- AGREGAR RESPUESTA -->
    <?php if ($ticket['estado'] !== 'Culminada'): ?>
        <div class="card">
            <h2>Agregar Respuesta</h2>
            <form method="POST" action="/Mesa_Ayuda/agente/responder">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                <div class="form-group">
                    <label>Mensaje</label>
                    <textarea name="mensaje" required placeholder="Describe cómo resolviste el problema..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
            </form>
        </div>
    <?php endif; ?>

</div>

<!-- MODAL CULMINAR -->
<?php if ($ticket['estado'] !== 'Culminada' && !empty($respuestas)): ?>
    <div class="modal-overlay" id="modal-solucion">
        <div class="modal-box">
            <h2>Culminar Ticket</h2>
            <p>Describe cómo resolviste el problema. Este texto quedará guardado como la solución del ticket.</p>
            <form method="POST" action="/Mesa_Ayuda/agente/culminar/<?= $ticket['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <div class="form-group">
                    <label>Solución</label>
                    <textarea name="solucion" required placeholder="Ej: Se reinició el servicio X y se verificó el acceso del usuario..." style="min-height:120px;"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('modal-solucion').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar y Culminar</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<style>
    /* ========== RESET Y BASE ========== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f5f5f5;
    }

    /* ========== HEADER ========== */
    .header {
        background: #1565C0;
        color: #fff;
        padding: 16px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ========== NAVEGACIÓN ========== */
    .nav-menu {
        background: #fff;
        padding: 0 32px;
        display: flex;
        gap: 8px;
        border-bottom: 1px solid #E0E0E0;
    }

    .nav-menu a {
        padding: 16px 20px;
        color: #616161;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        color: #1565C0;
        border-bottom-color: #1E88E5;
    }

    /* ========== CONTENIDO ========== */
    .content {
        padding: 32px;
        max-width: 900px;
        margin: 0 auto;
    }

    /* ========== TARJETAS ========== */
    .card {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .card h2 {
        margin-bottom: 16px;
        color: #0D47A1;
    }

    /* ========== GRID ========== */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .campo {
        margin-bottom: 12px;
    }

    .campo label {
        font-weight: 600;
        color: #616161;
        font-size: 13px;
        display: block;
    }

    .campo .valor {
        font-size: 15px;
        color: #212121;
        padding: 6px 0;
    }

    /* ========== BADGES ========== */
    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-Alta {
        background: #FFEBEE;
        color: #C62828;
    }

    .badge-Media {
        background: #FFF3E0;
        color: #E65100;
    }

    .badge-Baja {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-En.proceso {
        background: #E3F2FD;
        color: #0D47A1;
    }

    .badge-En {
        background: #FFF8E1;
        color: #F57F17;
    }

    .badge-Culminada {
        background: #E8F5E9;
        color: #2E7D32;
    }

    /* ========== BOTONES ========== */
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #1E88E5;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1565C0;
    }

    .btn-success {
        background: #43A047;
        color: #fff;
    }

    .btn-success:hover {
        background: #2E7D32;
    }

    .btn-secondary {
        background: #E0E0E0;
        color: #616161;
    }

    .btn-secondary:hover {
        background: #BDBDBD;
    }

    .btn-logout {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 12px;
    }

    .btn-logout:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .flex {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    /* ========== RESPUESTAS ========== */
    .respuesta {
        border-left: 3px solid #1E88E5;
        padding: 12px 16px;
        margin-bottom: 12px;
        background: #f8f9fa;
        border-radius: 0 6px 6px 0;
    }

    .respuesta .agente {
        font-weight: 600;
        color: #0D47A1;
    }

    .respuesta .fecha {
        font-size: 12px;
        color: #999;
    }

    .respuesta .mensaje {
        margin-top: 6px;
    }

    /* ========== FORMULARIO ========== */
    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #E0E0E0;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 80px;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #1E88E5;
    }

    /* ========== FIRMA ========== */
    .firma {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        display: inline-block;
    }

    .firma-valida {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .firma-invalida {
        background: #FFEBEE;
        color: #C62828;
    }

    /* ========== ALERTAS ========== */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        border: 1px solid #C8E6C9;
    }

    .alert-error {
        background: #FFEBEE;
        color: #C62828;
        border: 1px solid #FFCDD2;
    }

    /* ========== SOLUCIÓN ========== */
    .solucion-box {
        border-left: 3px solid #43A047;
        padding: 12px 16px;
        background: #f1f8f2;
        border-radius: 0 6px 6px 0;
    }

    /* ========== MODAL ========== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-box {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-box h2 {
        color: #0D47A1;
        margin-bottom: 8px;
    }

    .modal-box p {
        color: #616161;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 16px;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 12px;
            text-align: center;
        }

        .content {
            padding: 16px;
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }

        .nav-menu {
            overflow-x: auto;
            padding: 0 16px;
        }

        .nav-menu a {
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
        }

        .header-actions {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .modal-box {
            margin: 16px;
            padding: 20px;
        }

        .modal-actions {
            flex-direction: column;
        }

        .modal-actions .btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 500px) {
        .flex {
            flex-direction: column;
            width: 100%;
        }

        .flex .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

</body>
</html>