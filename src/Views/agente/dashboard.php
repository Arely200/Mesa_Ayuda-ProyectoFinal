<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Agente - Mesa de Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Mesa_Ayuda/public/css/estilos.css">
</head>
<body>


<header class="header">
    <div style="font-size:20px;font-weight:700;">Panel del Agente</div>
    <div>
        <span><?= htmlspecialchars($nombre) ?></span>
        <form method="POST" action="/Mesa_Ayuda/logout" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \Core\CSRF::generarToken() ?>">
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
        </form>
    </div>
</header>


<nav class="nav-menu">
    <a href="/Mesa_Ayuda/agente/dashboard" class="active"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="/Mesa_Ayuda/agente/tickets"><i class="bi bi-ticket-perforated"></i> Mis Tickets</a>
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

    <!-- BIENVENIDA -->
    <div class="bienvenida">
        <h1>Bienvenido, <?= htmlspecialchars($nombre) ?></h1>
        <p style="color:#757575;margin-top:8px;">Panel de gestión de tickets asignados</p>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="stats">
        <div class="stat-card stat-total">
            <div class="numero"><?= $totales['total'] ?? 0 ?></div>
            <div class="label">Total Asignados</div>
        </div>
        <div class="stat-card stat-proceso">
            <div class="numero"><?= $totales['proceso'] ?? 0 ?></div>
            <div class="label">En Proceso</div>
        </div>
        <div class="stat-card stat-espera">
            <div class="numero"><?= $totales['espera'] ?? 0 ?></div>
            <div class="label">En Espera</div>
        </div>
        <div class="stat-card stat-culminada">
            <div class="numero"><?= $totales['culminada'] ?? 0 ?></div>
            <div class="label">Culminados</div>
        </div>
    </div>

    <!-- TICKETS RECIENTES -->
    <div class="card">
        <h2>Tickets Recientes</h2>
        <?php if (empty($tickets)): ?>
            <p style="color:#999;">No tienes tickets asignados aún.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Colaborador</th>
                        <th>Categoría</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($tickets, 0, 5) as $t): ?>
                        <tr>
                            <td>#<?= $t['id'] ?></td>
                            <td><?= htmlspecialchars($t['titulo']) ?></td>
                            <td><?= htmlspecialchars($t['colaborador_nombre'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($t['categoria_nombre'] ?? 'N/A') ?></td>
                            <td><span class="badge badge-<?= $t['prioridad'] ?>"><?= $t['prioridad'] ?></span></td>
                            <td><span class="badge badge-<?= $t['estado'] ?>"><?= $t['estado'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($t['fecha_creacion'])) ?></td>
                            <td><a href="/Mesa_Ayuda/agente/ticket/<?= $t['id'] ?>" class="btn btn-primary">Ver</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>


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
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ========== BIENVENIDA ========== */
    .bienvenida {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    /* ========== ESTADÍSTICAS ========== */
    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .stat-card .numero {
        font-size: 36px;
        font-weight: bold;
    }

    .stat-card .label {
        color: #757575;
        font-size: 14px;
        margin-top: 4px;
    }

    .stat-total .numero {
        color: #0D47A1;
    }

    .stat-proceso .numero {
        color: #1E88E5;
    }

    .stat-espera .numero {
        color: #F57F17;
    }

    .stat-culminada .numero {
        color: #2E7D32;
    }

    /* ========== TARJETA ========== */
    .card {
        background: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .card h2 {
        margin-bottom: 16px;
        color: #0D47A1;
    }

    /* ========== TABLA ========== */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f5f5f5;
        text-align: left;
        padding: 12px 16px;
        color: #616161;
        font-weight: 600;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
    }

    /* ========== BADGES ========== */
    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
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

    /* ========== BOTONES ========== */
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
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

        .stats {
            grid-template-columns: 1fr 1fr;
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
    }

    @media (max-width: 500px) {
        .stats {
            grid-template-columns: 1fr;
        }
    }
</style>

</body>
</html>